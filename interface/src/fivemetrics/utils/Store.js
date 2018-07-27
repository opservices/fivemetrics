class Store {
  constructor(){
   if(! Store.instance){
     this._data = []
     Store.instance = this
   }
   return Store.instance
  }
}

const instance = new Store()

export default instance