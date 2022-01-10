<template>
  <div class="main">
    <v-btn @click="handleLogout" class="logout">
      Logout
    </v-btn>
    <v-card-title class="header">
      Product Filters
      <v-spacer></v-spacer>
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
      <v-select v-model="selectedHeaders" :items="headers" label="Select Columns" class="selectedHeaders" multiple solo return-object>
        <template v-slot:selection="{ item, index }" @click="hideHeaders">
          <v-chip v-if="index < 3">
            <span>{{ item.text }}</span>
          </v-chip>
          <span v-if="index === 2" class="grey--text caption">(+{{ selectedHeaders.length - 3 }} others)</span>
        </template>
      </v-select>
    </div>
    <v-toolbar
      dark
      color="teal"
    >
      <v-toolbar-title>State selection</v-toolbar-title>
      <v-autocomplete
        v-model="select"
        :loading="loading"
        :items="items"
        :search-input.sync="search"
        cache-items
        class="mx-4"
        flat
        hide-no-data
        hide-details
        label="What state are you from?"
        solo-inverted
      ></v-autocomplete>
      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-toolbar>
    <v-toolbar
      dark
      color="teal"
    >
      <v-toolbar-title>State selection</v-toolbar-title>
      <v-autocomplete
        v-model="select"
        :loading="loading"
        :items="items"
        :search-input.sync="search"
        cache-items
        class="mx-4"
        flat
        hide-no-data
        hide-details
        label="What state are you from?"
        solo-inverted
      ></v-autocomplete>
      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-toolbar>
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
      @page-count="pageCount = $event"
      :page.sync="page"
      :items-per-page="itemsPerPage"
      :header-props="{ sortIcon: null }"
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
        <img
          v-for="(image, index) in item.images.split(',')" :key="index"
          :src="image"
          :class="index == 0 ?'mainImg':'otherImg'"
        />
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
    page: 1,
    pageCount: 0,
    itemsPerPage: 10,
    dialogOpen: false,
    dialogMode: 1,
    search: '',
    dialogDelete: false,
    singleSelect: false,
    selected: [],
    selectedHeaders: [],
    headers: [],
    headersMap: [
      { text: 'id', value: 'id', sortable: true, align: "center", class: 'teal--text'},
      { text: 'category', value: 'category', sortable: true, width: "100px", align: "center", class: 'teal--text'},
      { text: 'name', value: 'name', sortable: true, width: "150px", align: "center", class: 'teal--text' },
      { text: 'model', value: 'model', sortable: true, width: "100px", align: "center", class: 'teal--text' },
      { text: 'sku', value: 'sku', sortable: false, width: "20%", align: "center" },
      { text: 'url', value: 'url', sortable: false, width: "20%", align: "center" },
      { text: 'location', value: 'location', sortable: false, width: "20%", align: "center" },
      { text: 'ean', value: 'ean', sortable: false, width: "20%", align: "center" },
      { text: 'jan', value: 'jan', sortable: false, width: "20%", align: "center" },
      { text: 'mpn', value: 'mpn', sortable: false, width: "20%", align: "center" },
      { text: 'upc', value: 'upc', sortable: false, width: "20%", align: "center" },
      { text: 'discount_price', value: 'discount_price', sortable: false, width: "20%", align: "center" },
      { text: 'price', value: 'price', sortable: true, width: "20%", align: "center", class: 'teal--text' },
      { text: 'stock_status', value: 'stock_status', sortable: false, width: "20%", align: "center" },
      { text: 'manufacturer', value: 'manufacturer', sortable: true, width: "130px", align: "center", class: 'teal--text' },
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
      this.products = [];
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
        .get("/api/products?search_key=" + this.search)
        .then((res) => {
          console.log(res);
          this.products = res.data;
          console.log("Maximus", this.products[5].images)
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
    saveProduct(selectedProduct) {
      if (selectedProduct.id === 0) {
        // Create
        window.axios.post("/api/products", selectedProduct)
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
    },
    hideHeaders() {
      console.log("Maximus is callings you!")
    }
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
  .v-list {
    background-color: teal;
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
  .mainImg {
    width: 50px;
    height: 30px;
  }
  .otherImg {
    width: 10px;
    height: 7px;
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
    max-width: 700px;
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
</style>
