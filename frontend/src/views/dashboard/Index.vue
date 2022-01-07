<template>
  <div class="main">
    <text-button @click="handleLogout">
      Logout
    </text-button>
    <v-card-title class="header">
      Product Filters
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
        class="search"
      ></v-text-field>
      <v-btn class="mb-2 search_btn" dark v-model="search" @click="getProducts">
        Search
      </v-btn>
      <v-select v-model="selectedHeaders" :items="headers" label="Select Columns" class="selectedHeaders" multiple outlined return-object>
        <template v-slot:selection="{ item, index }">
          <v-chip v-if="index < 2">
            <span>{{ item.text }}</span>
          </v-chip>
          <span v-if="index === 2" class="grey--text caption">(+{{ selectedHeaders.length - 2 }} others)</span>
        </template>
      </v-select>
    </v-card-title>
    <v-data-table
      :headers="showHeaders"
      :items="products"
      sort-by="calories"
      class="elevation-1"
      :search="search"
      :single-select="singleSelect"
      item-key="name"
      show-select
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
          <ProductDialog
            :dialogOpen="dialogOpen"
            :dialogMode="dialogMode"
            :selectProduct="selectProduct"
            :closeDialog="closeDialog"
            :saveProduct="saveProduct"
            :deleteProduct="deleteProduct"
          />
        </v-toolbar>
      </template>
      <template v-slot:[`item.images`]="{ images }">
        <v-menu transition="slide-x-transition" bottom right>
          <template v-slot:activator="{ on, attrs }">
            <img
              @click="images"
              v-bind="attrs"
              v-on="on"
              src="https://cdn.vseinstrumenti.ru/images/goods/instrument/dreli/430/2400x1600/51187473.jpg"
              style="width: 90px; height: 60px"
            />
          </template>
        </v-menu>
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
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="initialize"
        >
          Reset
        </v-btn>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import ProductDialog from './ProductDialog.vue'
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
    ProductDialog
  },
  data: () => ({
    dialogOpen: false,
    dialogMode: 1,
    search: '',
    dialogDelete: false,
    singleSelect: false,
    selected: [],
    selectedHeaders: [],
    headers: [],
    headersMap: [
      {
        text: 'category',
        align: 'start',
        sortable: false,
        value: 'category',
      },
      { text: 'name', value: 'name' },
      { text: 'model', value: 'model' },
      { text: 'sku', value: 'sku' },
      { text: 'url', value: 'url' },
      { text: 'location', value: 'location'},
      { text: 'ean', value: 'ean'},
      { text: 'jan', value: 'jan'},
      { text: 'mpn', value: 'mpn'},
      { text: 'upc', value: 'upc'},
      { text: 'discount_price', value: 'discount_price'},
      { text: 'price', value: 'price'},
      { text: 'stock_status', value: 'stock_status'},
      { text: 'manufacturer', value: 'manufacturer'},
      { text: 'description', value: 'description'},
      { text: 'attributes', value: 'attributes'},
      { text: 'images', value: 'images'},
      { text: 'date_added', value: 'date_added'},
      { text: 'date_parsing', value: 'date_parsing'},
      { text: 'date_modified', value: 'date_modified'},
      { text: 'quantity', value: 'quantity'},
      { text: 'status', value: 'status'},
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    products: [],
    editedIndex: -1,
    selectProduct: initProduct,
  }),

  created () {
    this.initialize()
    this.headers = Object.values(this.headersMap);
    this.selectedHeaders = this.headers;
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
    this.getProducts();
  },

  methods: {
    initialize () {
      this.products = [
        {
          "id": "61d83e7a3d32d65ce485e7f6",
          "category": "Ударные",
          "name": "SENMAO",
          "model": 3413,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 6057,
          "stock_status": false,
          "manufacturer": "SURELOGIC",
          "description": "Genoa, Georgia",
          "attributes": "Spencer",
          "images": "http://placehold.it/128x128",
          "date_added": "2016-05-20",
          "date_parsing": "2021-08-07",
          "date_modified": "2017-03-01",
          "quantity": 770,
          "status": true
        },
        {
          "id": "61d83e7a54412b2f5bcd5ffc",
          "category": "Ударные",
          "name": "ARCHITAX",
          "model": 3846,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 7280,
          "stock_status": false,
          "manufacturer": "COMDOM",
          "description": "Valle, Wyoming",
          "attributes": "Fuller",
          "images": "http://placehold.it/128x128",
          "date_added": "2021-01-31",
          "date_parsing": "2020-02-25",
          "date_modified": "2017-03-03",
          "quantity": 120,
          "status": false
        },
        {
          "id": "61d83e7a24b7f4bf81e1df52",
          "category": "Ударные",
          "name": "ILLUMITY",
          "model": 6596,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 1636,
          "stock_status": true,
          "manufacturer": "PHARMEX",
          "description": "Libertytown, District Of Columbia",
          "attributes": "Mckinney",
          "images": "http://placehold.it/128x128",
          "date_added": "2019-02-22",
          "date_parsing": "2019-06-14",
          "date_modified": "2015-03-07",
          "quantity": 859,
          "status": true
        },
        {
          "id": "61d83e7a468b58542d904b1d",
          "category": "Ударные",
          "name": "TETRATREX",
          "model": 4365,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 9566,
          "stock_status": true,
          "manufacturer": "CINASTER",
          "description": "Norvelt, Wisconsin",
          "attributes": "Vinson",
          "images": "http://placehold.it/128x128",
          "date_added": "2018-09-06",
          "date_parsing": "2019-07-31",
          "date_modified": "2021-05-30",
          "quantity": 208,
          "status": true
        },
        {
          "id": "61d83e7a6accd8abbccfafd3",
          "category": "Ударные",
          "name": "VICON",
          "model": 2469,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 1890,
          "stock_status": true,
          "manufacturer": "TELPOD",
          "description": "Dowling, Massachusetts",
          "attributes": "Collier",
          "images": "http://placehold.it/128x128",
          "date_added": "2018-07-12",
          "date_parsing": "2020-01-15",
          "date_modified": "2016-04-27",
          "quantity": 517,
          "status": true
        },
        {
          "id": "61d83e7aef29955e6c8e708c",
          "category": "Ударные",
          "name": "EQUICOM",
          "model": 3226,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 7516,
          "stock_status": true,
          "manufacturer": "BOVIS",
          "description": "Trexlertown, Palau",
          "attributes": "Bonner",
          "images": "http://placehold.it/128x128",
          "date_added": "2021-04-11",
          "date_parsing": "2014-08-16",
          "date_modified": "2015-02-08",
          "quantity": 878,
          "status": false
        },
        {
          "id": "61d83e7abf65357a52d80713",
          "category": "Ударные",
          "name": "IMAGEFLOW",
          "model": 4033,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 3714,
          "stock_status": true,
          "manufacturer": "VENOFLEX",
          "description": "Carlton, Minnesota",
          "attributes": "Perry",
          "images": "http://placehold.it/128x128",
          "date_added": "2018-04-24",
          "date_parsing": "2017-06-17",
          "date_modified": "2018-06-22",
          "quantity": 736,
          "status": false
        },
        {
          "id": "61d83e7ac2187a4ec3a20086",
          "category": "Ударные",
          "name": "LUMBREX",
          "model": 8591,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 2440,
          "stock_status": true,
          "manufacturer": "NIKUDA",
          "description": "Keller, California",
          "attributes": "Mitchell",
          "images": "http://placehold.it/128x128",
          "date_added": "2015-01-28",
          "date_parsing": "2019-06-04",
          "date_modified": "2016-08-11",
          "quantity": 910,
          "status": true
        },
        {
          "id": "61d83e7ad6e6d7f3555c8be4",
          "category": "Ударные",
          "name": "MATRIXITY",
          "model": 2691,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 9180,
          "stock_status": true,
          "manufacturer": "SARASONIC",
          "description": "Lemoyne, Delaware",
          "attributes": "Hopper",
          "images": "http://placehold.it/128x128",
          "date_added": "2020-12-20",
          "date_parsing": "2014-11-17",
          "date_modified": "2014-03-31",
          "quantity": 956,
          "status": false
        },
        {
          "id": "61d83e7a1ef346e6851f1793",
          "category": "Ударные",
          "name": "ISONUS",
          "model": 3046,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 3663,
          "stock_status": false,
          "manufacturer": "PORTICO",
          "description": "Kapowsin, New Mexico",
          "attributes": "Franco",
          "images": "http://placehold.it/128x128",
          "date_added": "2017-05-09",
          "date_parsing": "2016-08-12",
          "date_modified": "2021-06-15",
          "quantity": 135,
          "status": false
        },
        {
          "id": "61d83e7ac723d088910dcaad",
          "category": "Ударные",
          "name": "FROSNEX",
          "model": 6430,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 4584,
          "stock_status": false,
          "manufacturer": "BICOL",
          "description": "Waterview, Kentucky",
          "attributes": "Nunez",
          "images": "http://placehold.it/128x128",
          "date_added": "2019-07-30",
          "date_parsing": "2018-09-29",
          "date_modified": "2020-05-18",
          "quantity": 146,
          "status": true
        },
        {
          "id": "61d83e7a599c47f68ce06a14",
          "category": "Ударные",
          "name": "DANCERITY",
          "model": 1014,
          "sku": "",
          "url": "Ссылка",
          "location": "vseinstrumenti",
          "ean": "",
          "jan": "",
          "mpn": "",
          "upc": "",
          "discount_price": 0,
          "price": 8434,
          "stock_status": false,
          "manufacturer": "ATGEN",
          "description": "Belmont, Arkansas",
          "attributes": "Bauer",
          "images": "http://placehold.it/128x128",
          "date_added": "2015-02-03",
          "date_parsing": "2018-04-08",
          "date_modified": "2016-12-21",
          "quantity": 124,
          "status": true
        }
      ]
    },

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
    getProducts() {
      window.axios
        .get("/api/products?search_key" + this.search)
        .then((res) => {
          console.log(res);
          this.products = res.data;
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
    saveProduct(selectedProduct) {
      if (selectedProduct.id === 0) {
        // Create
        window.axios.post()
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
        window.axios.put()
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
      window.axios.delete()
        .then(() => {
          this.closeDialog()
          this.getProducts()
        })
        .catch((err) => {
          console.log('err', err)
        })
    },
    async handleLogout() {
      await this.$auth.logout();
      await this.$router.push({ name: "login" });
    }
  }
}
</script>

<style scoped>
  .main {
    padding: 10px;
    margin: 30px;
    background-color: #efefef;
  }
  .header {
    display: flex;
    align-content: center;
    justify-content: center;
  }
  .selectedHeaders {
    margin-left: 20px;
    margin-top: 26px;
  }
  .v-list {
    background-color: #55b4ff;
  }
  .addProduct_btn {
    background-color: #1867c0 !important;
  }
  .search_btn {
    margin-top: 22px;
  }
  .v-card__title {
    margin-top: -45px;
  }
  .formTitle {
    margin-top: 60px;
  }
</style>
