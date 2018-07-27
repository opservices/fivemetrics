export default class LocalStorage {
	static read(key, item='fivemetrics') {
    let ls = {};
    if (global.localStorage) {
      try {
        ls = JSON.parse(global.localStorage.getItem(item)) || {}
      } catch(e) {/*Ignore*/}
    }
    return ls[key];
  }

  static write(key, value, item='fivemetrics') {
    if (global.localStorage) {
      const before = JSON.parse(global.localStorage.getItem(item))
      global.localStorage.setItem(item, JSON.stringify(Object.assign({}, before, {[key]: value})));
    }
  }
}
