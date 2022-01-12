<template>
  <v-app>
    <div class="main">
      <v-btn @click="handleLogout" class="logout">
        Logout
      </v-btn>
      <v-card-title class="header">
        <div style="display: flex;">
          <span style="margin-right: 20px; margin-top: 10px">
            Product Filters
          </span>
          <v-spacer></v-spacer>
          <v-select
            :items="items"
            label="Choose Database..."
            dense
            filled
          ></v-select>
        </div>
      </v-card-title>
      <div class="search_tab">
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
          class="search_input"
        ></v-text-field>
        <v-btn class="mb-2 search_btn" style="background-color: teal; color: white" @click="getProducts">
          Search
        </v-btn>
        <v-btn @click="settings" class="setting_btn">
          Setting
        </v-btn>
        <v-select v-model="selectedHeaders" :items="headers" label="Select Columns" class="selectedHeaders" multiple solo return-object>
          <template v-slot:selection="{ item, index }">
            <v-chip v-if="index < 3">
              <span>{{ item.text }}</span>
            </v-chip>
            <span v-if="index === 2" class="grey--text caption">(+{{ selectedHeaders.length - 3 }} others)</span>
          </template>
        </v-select>
      </div>
      <div style="display: flex; width: 1000px; margin-left: 30px;">
        <SelectionSearch
          type="brand"
          title="Brands"
          :states="brands"
          :changeFilter="changeFilter"
          :clearFilter="clearFilter"
          style="border-radius: 4px"
        />
        <SelectionSearch
          type="category"
          title="Categories"
          :states="categories"
          :changeFilter="changeFilter"
          :clearFilter="clearFilter"
          style="margin-left: 20px; border-radius: 4px"
          />
      </div>
      <div class="text-center pt-2 pagination">
        <v-pagination
          v-model="page"
          :length="pageCount"
          :total-visible="7"
          color="teal"
          class="pagination"
        ></v-pagination>
        <v-text-field
          :value="itemsPerPage"
          v-model="itemsPerPage"
          label="Rows:"
          type="number"
          min="-1"
          max="15"
          @input="itemsPerPage = parseInt($event, 10)"
          class="centered-input"
        ></v-text-field>
      </div>
      <v-data-table
        :headers="showHeaders"
        :items="products"
        sort-by="calories"
        class="mainTable"
        :search="search"
        :single-select="singleSelect"
        item-key="name"
        show-select
        :items-per-page="itemsPerPage"
        :header-props="{ sortIcon: null }"
        hide-default-footer
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
            <v-toolbar-title>Product List</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-btn
              class="mb-2 addProduct_btn"
              @click="createProductDialog"
            >
              Add Product
            </v-btn>
            <ProductDialog
              :dialogOpen="dialogOpen"
              :dialogMode="dialogMode"
              :selectProduct="selectProduct"
              :headerName="headerName"
              :closeDialog="closeDialog"
              :saveProduct="saveProduct"
              :deleteProduct="deleteProduct"
              :sortActive="sortActive"
              :otherImgShow="otherImgShow"
              :showOrNotOtherImg="showOrNotOtherImg"
              :changeImgSize="changeImgSize"
              :selectedImgSize="selectedImgSize"
            />
          </v-toolbar>
        </template>
        <template v-slot:[`item.name`]="{ item }" class="nameWidth">
          <a @click="editProductDialog(item)" style="color: teal">
            {{ item.name }}
          </a>
        </template>
        <template v-slot:[`item.url`]="{ item }">
          <a v-bind:href="item.url" style="color: teal">
            Cсылка
          </a>
        </template>
        <template v-slot:[`item.location`]="{ item }">
          <a v-bind:href="item.url" style="color: teal">
            {{ item.location }}
          </a>
        </template>
        <template v-slot:[`item.stock_status`]="{ item }">
          <span :class="item.stock_status.slice(0,3) === 'Нет' ? 'false' : 'true'">
            {{ item.stock_status }}
          </span>
        </template>
        <template v-slot:[`item.manufacturer`]="{ item }">
          {{ item.manufacturer }}
        </template>
        <template v-slot:[`item.description`]="{ item }">
          <v-tooltip bottom open-delay="500" max-width="300px">
            <template v-slot:activator="{ on, attrs }">
              <p class="truncate" v-bind="attrs"
                v-on="on">
                {{ getCutText(item.description) }}[...]
              </p>
            </template>
            <span class="tooltip">{{ getFormated(item.description) }}</span>
          </v-tooltip>
        </template>
        <template v-slot:[`item.attributes`]="{ item }">
          <v-tooltip bottom open-delay="500" max-width="300px">
            <template v-slot:activator="{ on, attrs }">
              <p class="truncate" v-bind="attrs"
                v-on="on">
                {{ getCutText(item.attributes) }}[...]
              </p>
            </template>
            <span class="tooltip">{{ getFormated(item.attributes) }}</span>
          </v-tooltip>
        </template>
          <template v-slot:[`item.images`]="{ item }">
            <div v-if="otherImgShow">
              <div v-if="selectedImgSize === '50х50' || selectedImgSize === ''">
                <img
                  v-for="(image, index) in item.images.split(',')" :key="index"
                  :src="image"
                  :class="index == 0 ? 'mainImg1':'otherImg1'"
                />
              </div>
              <div v-if="selectedImgSize === '100х100'">
                <img
                  v-for="(image, index) in item.images.split(',')" :key="index"
                  :src="image"
                  :class="index == 0 ? 'mainImg2':'otherImg2'"
                />
              </div>
              <div v-if="selectedImgSize === '150х150'">
                <img
                  v-for="(image, index) in item.images.split(',')" :key="index"
                  :src="image"
                  :class="index == 0 ? 'mainImg3':'otherImg3'"
                />
              </div>
              <div v-if="selectedImgSize === '200х200'">
                <img
                  v-for="(image, index) in item.images.split(',')" :key="index"
                  :src="image"
                  :class="index == 0 ? 'mainImg4':'otherImg4'"
                />
              </div>
            </div>
            <div v-if="!otherImgShow">
              <div v-if="selectedImgSize === '50х50' || selectedImgSize === ''">
                <img
                  :src="item.images.split(',')[0]"
                  class="mainImg1"
                />
              </div>
              <div v-if="selectedImgSize === '100х100'">
                <img
                  :src="item.images.split(',')[0]"
                  class="mainImg2"
                />
              </div>
              <div v-if="selectedImgSize === '150х150'">
                <img
                  :src="item.images.split(',')[0]"
                  class="mainImg3"
                />
              </div>
              <div v-if="selectedImgSize === '200х200'">
                <img
                  :src="item.images.split(',')[0]"
                  class="mainImg4"
                />
              </div>
            </div>
          </template>
        <template v-slot:[`item.status`]="{ item }">
          <span :class="item.status == 1 ? 'true' : 'false'">
            {{ item.status == 1 ? 'Включен' : 'Выключен' }}
          </span>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editProductDialog(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            @click="deleteProductDialog(item)"
          >
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
      <div class="text-center pt-2 pagination">
        <v-pagination
          v-model="page"
          :length="pageCount"
          :total-visible="7"
          color="teal"
          class="pagination"
        ></v-pagination>
        <v-text-field
          :value="itemsPerPage"
          v-model="itemsPerPage"
          label="Rows:"
          type="number"
          min="-1"
          max="15"
          @input="itemsPerPage = parseInt($event, 10)"
          class="centered-input"
        ></v-text-field>
      </div>
    </div>
  </v-app>
</template>

<script>
import ProductDialog from './ProductDialog.vue';
import SelectionSearch from './SelectionSearch.vue';
const initProduct = {
  id: 0,
  category: '',
  name:'',
  model:'',
  sku:'',
  url:'',
  location:'',
  ean:'',
  jan:'',
  mpn:'',
  upc:'',
  discount_price:'',
  price:'',
  stock_status:'',
  manufacturer:'',
  description:'',
  attributes:'',
  images:'',
  date_added:'',
  date_parsing:'',
  date_modified:'',
  quantity:'',
  status:'',
}
export default {
  components: {
    ProductDialog,
    SelectionSearch,
  },
  data: () => ({
    cat: [],
    brands: [],
    categories: [],
    selectedImgSize: '',
    otherImgShow: true,
    items: [],
    page: 1,
    pageCount: 10,
    itemsPerPage: 10,
    dialogOpen: false,
    dialogMode: 1,
    search: '',
    brand: '',
    category: '',
    dialogDelete: false,
    singleSelect: false,
    selected: [],
    selectedHeaders: [],
    headers: [],
    headersMap: [
      { text: 'id', value: 'id', sortable: true, align: "center"},
      { text: 'category', value: 'category', sortable: true, width: "100px", align: "center", class: ''},
      { text: 'name', value: 'name', sortable: true, width: "150px", align: "center", class: '' },
      { text: 'model', value: 'model', sortable: true, width: "100px", align: "center", class: '' },
      { text: 'sku', value: 'sku', sortable: false, width: "20%", align: "center" },
      { text: 'url', value: 'url', sortable: false, width: "20%", align: "center" },
      { text: 'location', value: 'location', sortable: false, width: "20%", align: "center" },
      { text: 'ean', value: 'ean', sortable: false, width: "20%", align: "center" },
      { text: 'jan', value: 'jan', sortable: false, width: "20%", align: "center" },
      { text: 'mpn', value: 'mpn', sortable: false, width: "20%", align: "center" },
      { text: 'upc', value: 'upc', sortable: false, width: "20%", align: "center" },
      { text: 'discount_price', value: 'discount_price', sortable: false, width: "20%", align: "center" },
      { text: 'price', value: 'price', sortable: true, width: "20%", align: "center", class: '' },
      { text: 'stock_status', value: 'stock_status', sortable: false, width: "20%", align: "center" },
      { text: 'manufacturer', value: 'manufacturer', sortable: true, width: "130px", align: "center", class: '' },
      { text: 'description', value: 'description', sortable: false, width: "20%", align: "center" },
      { text: 'attributes', value: 'attributes', sortable: false, width: "20%", align: "center" },
      { text: 'images', value: 'images', sortable: false, width: "20%", align: "center" },
      { text: 'date_added', value: 'date_added', sortable: false, width: "20%", align: "center" },
      { text: 'date_parsing', value: 'date_parsing', sortable: false, width: "20%", align: "center" },
      { text: 'date_modified', value: 'date_modified', sortable: false, width: "20%", align: "center" },
      { text: 'quantity', value: 'quantity', sortable: false, width: "20%", align: "center" },
      { text: 'status', value: 'status', sortable: false, width: "20%", align: "center" },
      { text: 'Actions', value: 'actions', sortable: false, width: "20%", align: "center" },
    ],
    products: [],
    editedIndex: -1,
    selectProduct: initProduct,
  }),

  created () {
    this.headers = this.headersMap;
    this.selectedHeaders = this.headers;
    this.headerName = this.headersMap;
    this.getProducts();
    this.getBrand();
    this.getCategory();
  },

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Add Product' : 'Edit Product'
    },
    showHeaders () {
      return this.headers.filter(s => this.selectedHeaders.includes(s));
    }
  },

  mounted() {
  },

  watch: {
    page(val) {
      console.log('page', val);
      this.getProducts(this.search, this.brand, this.category, val, this.itemsPerPage);
    },
    itemsPerPage(val) {
      console.log('itemsPerPage', val);
      this.getProducts(this.search, this.brand, this.category, this.page, val);
    },
  },

  methods: {
    showOrNotOtherImg() {
      this.otherImgShow = !this.otherImgShow;
    },
    changeImgSize(size) {
      console.log('changeImgSize', size);
      console.log("Selected Image", this.selectedImgSize)
      this.selectedImgSize = size;
    },
    sortActive () {
      console.log("Rex")
      for(let i = 0; i < this.headersMap.length; i++) {
        if(this.headersMap[i].sortable === true) {
          this.headersMap[i].class = "teal--text";
        } else {
          this.headersMap[i].class = "";
        }
      }
    },
    getFormated(item) {
      let text = item;
      let removedTags = text.replace(/<\/?[^>]+(>|$)/g, "");
      let styledText = removedTags.replaceAll('<br>', '\n');
      return styledText;
    },
    getCutText(item) {
      let text = item;
      let removedTags = text.replace(/<\/?[^>]+(>|$)/g, "");
      let styledText = removedTags.replaceAll('<br>', '\n');
      let cutText = styledText.slice(0, 30)
      return cutText;
    },

    // Dialog Parts
    showDialog(dialogMode) {
      this.dialogOpen = true
      this.dialogMode = dialogMode
    },
    closeDialog() {
      this.dialogOpen = false
    },
    createProductDialog() {
      this.selectProduct = initProduct;
      this.showDialog(1)
    },
    editProductDialog(Product) {
      this.selectProduct = Product
      this.showDialog(2)
    },
    deleteProductDialog(Product) {
      this.selectProduct = Product
      this.showDialog(0)
    },
    settings() {
      this.showDialog(9)
    },
    changeFilter(type, val) {
      console.log("Seleceted Items", type, val);
      if(type === 'brand') {
        this.brand = val;
      }
      else if (type === 'category') {
        this.category = val;
      }
      this.getProducts(this.search, this.brand, this.category);
    },
    clearFilter(type) {
      if(type === 'brand') {
        this.brand = '';
      }
      else if (type === 'category') {
        this.category = '';
      }
      this.getProducts(this.search, this.brand, this.category);
    },

    // Api parts
    getProducts(search = '', brand = '', category = '', page = 1, limit = 10) {
      window.axios
        .get(`/api/products?search_key=${search}&manufacturer=${brand}&category=${category}&page=${page}&limit=${limit}`)
        .then((res) => {
          console.log(res);
          this.products = res.data.products;
          this.page = parseInt(res.data.page);
          this.pageCount = res.data.total_pages;
          console.log('pageCount', this.pageCount);
          // vm.$forceUpdate();
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
    getCategory() {
      window.axios
        .get("/api/products/getCategories")
        .then((res) => {
          console.log(res);
          if (res.data.length > 0) {
            this.categories = res.data.map(item => item.category);
            console.log("categ", this.categories)
            for(let i = 0; i < this.categories.length; i ++) {
              this.categories[i].split('|')
            }
            console.log("cate", this.cat)
          }
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
    getBrand() {
      window.axios
        .get("api/products/getBrands")
        .then((res) => {
          console.log(res);
          if (res.data.length > 0) {
            this.brands = res.data.map(item => item.manufacturer);
          }
        }).catch((err) => {
          console.log("err", err);
        });
    },
    saveProduct(selectedProduct) {
      if (selectedProduct.id === 0) {
        // Create
        window.axios.post('/api/products', selectedProduct)
          .then(() => {
            this.closeDialog()
            this.getProducts()
          })
          .catch((err) => {
            console.log('err', err)
          })
      }
      else {
        // Update
        window.axios.put(`/api/products/${selectedProduct.id}`, selectedProduct)
          .then(() => {
            this.closeDialog()
          })
          .catch((err) => {
            console.log('err', err)
          })
      }
    },

    deleteProduct(ProductId) {
      console.log('ProductId', ProductId)
      // Delete
      window.axios.delete(`/api/products/${ProductId}`)
        .then(() => {
          this.closeDialog()
          this.getProducts()
          console.log("DELETING")
        })
        .catch((err) => {
          console.log('err', err)
        })
    },
    async handleLogout() {
      await this.$auth.logout();
      await this.$router.push({ name: "login" });
    },
  }
}
</script>

<style scoped>
  .main {
    background-color: #efefef;
  }
  .v-data-table >>> td {
    border: 0.5px solid gray;
    font-size: 12px !important;
    padding: 5px !important;
  }
  .v-data-table >>> th {
      border: 0.5px solid gray;
      font-size: 12px !important;
      padding: 5px !important;
      background-color: white !important;
  }
  .v-data-table >>> tr:nth-of-type(odd) {
    background-color: #ebebeb;
  }
  .header {
    margin-top: 20px !important;
    padding: 0px;
    margin-left: 30px;
  }
  .selectedHeaders {
    margin-left: 20px;
    margin-top: 32px;
    width: 200px;
  }
  .search_btn {
    margin-top: 22px;
  }
  .search_input {
    width: 100px;
  }
  .v-card__title {
    margin-top: -45px;
  }
  .formTitle {
    margin-top: 60px;
  }
  .truncate {
    max-width: 100px;
    white-space: initial;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0px !important;
  }
  .mainImg1 {
    width: 50px;
    height: 30px;
  }
  .mainImg2 {
    width: 100px;
    height: 60px;
  }
  .mainImg3 {
    width: 150px;
    height: 80px;
  }
  .mainImg4 {
    width: 200px;
    height: 120px;
  }
  .otherImg1 {
    width: 10px;
    height: 7px;
  }
  .otherImg2 {
    width: 20px;
    height: 14px;
  }
  .otherImg3 {
    width: 30px;
    height: 21px;
  }
  .otherImg4 {
    width: 40px;
    height: 28px;
  }
  .false {
    color: red;
  }
  .true {
    color: green;
  }
  .pagination{
    float: right;
  }
  .mainTable {
    margin-top: 80px;
    padding: 5px;
  }
  .centered-input >>> input {
    text-align: center;
  }
  .centered-input {
    width: 100px;
    margin-top: -6px;

  }
  .search_tab{
    display: flex;
    align-items: center;
    max-width: 800px;
    margin-left: 30px;
    margin-top: -30px !important;
  }
  .logout {
    margin-right: 30px;
    margin-top: 30px;
    float: right;
    background-color: teal !important;
    color: white;
  }
  .setting_btn {
    background-color: teal !important;
    color: white;
    margin-left: 20px;
    margin-top: 13px;
  }
  .filters {
    width: 800px;
    display: flex;
  }
  .addProduct_btn {
    background-color: teal !important;
    color: white;
  }
  .notShow {
    display: none;
  }
</style>
