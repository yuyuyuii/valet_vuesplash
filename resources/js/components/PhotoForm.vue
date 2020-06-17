<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a Photo</h2>
    <div v-show="loading" class="panel">
      <Loader> Sending your photo...</Loader>
    </div>
    <form  v-show="! loading" class="form" @submit.prevent="submit">
      <div class="errors" v-if="errors">
        <ul v-if="errors.photo">
          <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <input type="file" class="form__item" @change="onFileChange">
      <output class="form__output" v-if="preview">
        <img :src="preview" alt="">
      </output>
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
import Loader from './Loader.vue'

export default {
  components:{
    Loader
  },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      preview: null,
      photo: null,
      errors: null
    }
  },
  methods: {
    //フォームでファイルが選択されたら実行
    onFileChange(event){
      //何も選択されていなかったら処理を中断
      if(event.target.files.length === 0){
        this.reset()//previewをリセット
        return false
      }
      //ファイルが画像じゃなかったら中断
      if(! event.target.files[0].type.match('image.*')){
        this.reset()//previewをリセット
        return false
      }

      //FileReaderクラスのインスタンス取得 html5のFileReaderを使用
      const reader = new FileReader()

      //ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e =>{
        /**
         * previewに読み込み結果(データURL)を代入する
         * previewに値が入ると<output>煮付けたv-ifがtrue判定
         * また<output>内部の<img>のsrc属性はpreviewの値を参照しているので、画像が表示される
         */
        this.preview = e.target.result//ここに画像のデータが入っている
      }

      //ファイル読み込み。
      // 読み込まれたファイルはデータURL形式で受け取れる
      reader.readAsDataURL(event.target.files[0]) //これでプレビューを表示している
      this.photo = event.target.files[0]
    },
    //入力欄の値とプレビュー表示をクリアするメソッド
    reset(){
      this.preview = ''
      this.photo = null
      this.$el.querySelector('input[type="file"]').value = null
    },
    async submit(){
      // submitを押したらローディングをtrueにする
      this.loading = true
      //HTML5 の FormData APIを使用
      const formData = new FormData()
      formData.append('photo', this.photo)
      const response = await axios.post('/api/photos', formData)
      //postしたらローディングを非表示にする
      this.loading = false
      if (response.status === UNPROCESSABLE_ENTITY){
        this.errors = response.data.errors
        return false
      }

      this.reset();
      this.$emit('input', false)
      if(response.status === CREATED){
        this.$store.commit('error/setCode', response.status)
        return false
      }
      this.$router.push(`/photos/${response.data.id}`)
    }
  }
}
</script>