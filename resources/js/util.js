/**
 * クッキーの値を取得する
 * @param {String}seachKey 検索するキー
 * @returns {String} キーに対応する値
 */

export function getCookieValue(seachKey) {
  if (typeof seachKey === 'undefined') {
    
    return '';
  }

  let val = '';

  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=');
    if (key === seachKey) {
      console.log(key)
      console.log(value);
      return val = value;
    }
  })
  return val;

  // こんな感じに受け取れる -> name=12345;token=67890;key=abcde

}