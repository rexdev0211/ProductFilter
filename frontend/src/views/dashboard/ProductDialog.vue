<template>
  <v-dialog
    v-model="dialogOpened"
    persistent
    max-width="700px"
    >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
      class="mb-2 addProduct_btn"
      v-bind="attrs"
      v-on="on"
      >
      Add Product
      </v-btn>
    </template>
      <v-card>
        <v-card-title>
          <span v-if="dialogMode === 1" class="text-h5 formTitle">Add Product</span>
          <span v-else-if="dialogMode === 2" class="text-h5 formTitle">Edit Product</span>
          <span v-else class="text-h5 formTitle">Delete Product</span>
        </v-card-title>
        <v-card-text>
          <v-container v-if="dialogMode > 0">
            <v-row>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.category"
                label="Category"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.name"
                label="Product Name"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.model"
                label="Model"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.sku"
                label="Sku"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.url"
                label="Url"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.location"
                label="Location"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.ean"
                label="Ean"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.jan"
                label="Jan"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.mpn"
                label="Mpn"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.upc"
                label="Upc"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.discount_price"
                label="Discount_price"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.price"
                label="Price"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.stock_status"
                label="Stock_status"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.manufacturer"
                label="Manufacturer"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.description"
                label="Description"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.attributes"
                label="Attributes"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.images"
                label="Images"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.date_added"
                label="Date_added"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.date_parsing"
                label="Date_parsing"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.date_modified"
                label="Date_modified"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.quantity"
                label="Quantity"
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                v-model="selectProduct.status"
                label="Status"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <v-container v-else>
            <v-card-title class="text-h5 formTitle">Are you sure you want to delete this item?</v-card-title>
          </v-container>
        </v-card-text>
        <v-card-actions>
        <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="closeDialogOpened"
          >
              Cancel
          </v-btn>
          <v-btn
            v-if="dialogMode !== 0"
            color="blue darken-1"
            text
            @click="saveProduct(selectProduct)"
          >
              Save
          </v-btn>
          <v-btn
            v-else
            color="blue darken-1"
            text
            @click="deleteProduct(selectProduct.id)"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script>
export default {
  name: 'ProductDialog',
  props: {
    dialogOpen: Boolean,
    dialogMode: Number,
    selectProduct: Object,
    closeDialog: Function,
    saveProduct: Function,
    deleteProduct: Function
  },
  data: () => ({
    dialogOpened: false
  }),
  watch: {
    dialogOpen() {
      this.dialogOpened = this.dialogOpen;
    }
  },
  methods: {
    closeDialogOpened() {
      this.dialogOpened = false;
      this.closeDialog();
    }
  }
}
</script>

<style scoped>
  .member-dialog {
    width: 100%;
    padding: 20px;
  }
  .addProduct_btn {
    background-color: teal !important;
    color: white;
  }
</style>