<template>
  <div>
    <v-card>
      <v-card-title>
        <v-text-field
          append-icon="search"
          :label="$t('Search')"
          single-line
          hide-details
          v-model="search"
        >
        </v-text-field>

        <v-tooltip bottom>
            <v-btn 
              color="teal"
              class="ml-3 mr-0"
              fab dark 
              slot="activator"
              @click.native.stop="openDialog"
            >
              <v-icon>add</v-icon>
            </v-btn>
            <span>{{ $t('Add') }}</span>
          </v-tooltip>

        <v-dialog   
          v-model="dialog"
          fullscreen
          :overlay="false"
          scrollable
          @keydown.esc="dialog = false"
          @keydown.ctrl="save"
        >
      
          <v-card tile class="vcard">
            <v-toolbar card dark color="primary">
              <v-btn icon @click.native="dialog = false" dark>
                <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>

               <v-expansion-panel popout>
                 <v-expansion-panel-content v-model="value">
                   <div slot="header" class="title">
                      <font-awesome-icon :icon="['far', 'file-alt']" class="mr-1" />
                      {{ $t('Content') }}
                   </div>

                 <div class="pa-4">
                  <v-form v-model="valid" ref="form">
                    <v-text-field 
                      :label="$t('Title')" 
                      v-model="editedItem.title"
                      :rules="[
                                v => !!v || $t('Insert title'),
                                v => (v && v.length > 4) || $t('Insert at least 5 characters')
                              ]"
                      required
                    >
                    </v-text-field>

                    <label class="body-2">
                      {{ $t('Content') }}
                    </label>
                    <vue-editor
                      v-model="editedItem.content" 
                      useCustomImageHandler
                      @imageAdded="handleImageAdded" 
                    ></vue-editor>
                    </v-form>
                  </div>
                </v-expansion-panel-content>

                <v-expansion-panel-content>
                   <div slot="header" class="title">
                      <font-awesome-icon :icon="['far', 'image']" class="mr-1" />
                      {{ $t('Images') }}
                   </div>
                   <div class="pa-4">
                    <vue-dropzone ref="myVueDropzone" id="dropzone" 
                      :options="dropzoneOptions" 
                      v-on:vdropzone-sending="sendingEvent" 
                      v-on:vdropzone-removed-file="removedFileEvent"
                      v-on:vdropzone-success="successEvent">
                    </vue-dropzone>
                   </div>
                  </v-expansion-panel-content>
                </v-expansion-panel>

                <v-card-actions class="pr-0 mr-0 mt-4">
                  <v-spacer></v-spacer>

                  <v-btn color="success"
                    :disabled="!valid" 
                    :loading="loading"
                    @click.native="save"
                  >
                    <font-awesome-icon icon="check" class="mr-2" />{{ $t('Save') }}
                  </v-btn>

                  <v-btn color="error" @click.native="closeDialog">
                    <font-awesome-icon icon="times" class="mr-2" />{{ $t('Cancel') }}
                  </v-btn>

                </v-card-actions>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-card-title>
    </v-card>

    <v-data-table
      :no-data-text="$t('No data')"
      :headers="headers"
      :items="items"
      hide-actions
      :loading="loading"
      :search="search"
      class="elevation-1"
      ref="sortableTable"
    >

    <template slot="items" slot-scope="props">
      <tr class="sortableRow" :key="itemKey(props.item)">

        <td class="px-1 drag">
          <v-btn icon class="sortHandle drag-btn">
            <v-icon>drag_handle</v-icon>
          </v-btn>
        </td>

        <td>{{ props.item.title }}</td>

        <td v-html="props.item.short_text"></td>

        <td>
           <v-switch
              v-model="props.item.nav"
              color="primary"
              @click.native="updateVisibility(props.item)" 
            ></v-switch>
        </td>

        <td class="justify-center layout px-0">
        
          <v-tooltip bottom>
            <v-btn 
              icon 
              class="mx-0" 
              slot="activator"
              @click="editItem(props.item)" 
            > 
              <v-icon color="teal">edit</v-icon>
            </v-btn>
            <span>{{ $t('Edit') }}</span>
          </v-tooltip>

          <v-tooltip bottom>
            <v-btn 
              icon 
              class="mx-0" 
              slot="activator"
              @click="deleteItem(props.item)" 
            > 
              <v-icon color="pink">delete</v-icon>
            </v-btn>
            <span>{{ $t('Delete') }}</span>
          </v-tooltip>
           
        </td>
      </tr>
    </template>

    <v-alert slot="no-results" :value="true" color="error" icon="warning">
        {{ $t('No search results for') }} "{{ search }}".
    </v-alert>
      
    </v-data-table>

     <v-snackbar
      bottom
      v-model="snackbar"
    >
      {{ snackbarText }}
      <v-btn flat dark @click.native="snackbar = false"><v-icon>clear</v-icon></v-btn>
    </v-snackbar>

  </div>
</template>

<script>
  import Sortable from 'sortablejs'
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.css'
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
  import { VueEditor, Quill } from 'vue2-editor'

  export default {
    components: { vueDropzone: vue2Dropzone, VueEditor, FontAwesomeIcon},

    data() {
      return {
        loading: true,
        valid: false,
        dialog: false,
        value: true,
        snackbar: false,
        snackbarText: '',
        search: '',
        itemKeys: new WeakMap(),
        currentItemKey: 0,
        items: [],
        editedIndex: -1,

        editedItem: {
          title: null,
          content: null,
          formToken: null
        },

        defaultItem: {
          title: null,
          content: null,
          formToken: null
        },

        headers: [
          { sortable: false, text: this.$t("Order"), value: "order" },
          { sortable: false, text: this.$t("Title"), value: "title" },
          { sortable: false, text: this.$t("Content"), value: "content" },
          { sortable: false, text: this.$t("Show in nav"), value: "nav" },
          { sortable: false, text: this.$t("Actions"), value: "actions" }
        ],

        dropzoneOptions: {
          url: 'api/images/store',
          thumbnailWidth: 265,
          thumbnailHeight: 149,
          maxFilesize: 15,
          maxFiles: 99,
          addRemoveLinks: true,
          dictDefaultMessage: this.$t('Drop files here to upload'),
          dictRemoveFile: this.$t('Remove file'),
          headers: { 'Authorization': `Bearer ${window.sessionStorage ? sessionStorage.getItem('token') : ''}` },
          params: { type: 'page', from_dropzone: 1 }
        },
      };
    },

    computed: {
      formTitle() {
        return this.editedIndex === -1
          ? this.$t("New page")
          : this.$t("Edit page") + ' (' + this.editedItem.title + ')' 
      },

      formIcon() {
        return this.editedIndex === -1
          ? 'plus-circle'
          : 'pencil-alt'
      }
    },

    watch: {
      dialog(val) {
        val || this.closeDialog()
      }
    },

    created() {
      this.$store.dispatch("setPageTitle", this.$t('Pages'))

      axios
        .get("/api/pages")
        .then(response => {
          this.items = response.data
          this.loading = false
        })
        .catch(error => {
          console.log(error)
        })
    },

    mounted() {
      /* eslint-disable no-new */
      // Needed to add this line to pass vue-utils test
      if(document.getElementsByTagName("tbody")[0])
      new Sortable(
        this.$refs.sortableTable.$el.getElementsByTagName("tbody")[0],
        {
          draggable: ".sortableRow",
          handle: ".sortHandle",
          onEnd: this.dragReorder
        }
      );
    },

    methods: {

       handleImageAdded(file, Editor, cursorLocation, resetUploader) {
        var formData = new FormData();
        formData.append('file', file)
        formData.append('type', 'page')
        formData.append('token', this.editedItem.formToken)
        formData.append('id', this.editedItem.id || 0)

        axios
         .post("/api/images/store", formData)
         .then((result) => {
            let url = result.data
            Editor.insertEmbed(cursorLocation, 'image', '/photos/upload/thumbs/' + url)
            resetUploader();  
          })
          .catch((err) => {
            console.log(err);
          })
      },

      successEvent(file, response) {
        file.previewElement.querySelector('[data-dz-name]').innerHTML = response
      },

      sendingEvent(file, xhr, formData) {
        formData.append('token', this.editedItem.formToken)
        formData.append('id', this.editedItem.id || 0)
      },

      removedFileEvent(file, error, xhr) {
        axios
          .delete("/api/images/destroy", {
            params: { url: file.previewElement.querySelector('[data-dz-name]').innerHTML }
          })
          .then(response => {
            this.loading = false
          })
          .catch(error => {
             this.$swal(this.$t('Error'), error, 'error')
          });
      },

      dragReorder({ oldIndex, newIndex }) {
        const movedItem = this.items.splice(oldIndex, 1)[0]
        this.items.splice(newIndex, 0, movedItem)
        this.loading = true

        axios
          .post("/api/pages/reorder", {
            order: this.items.map(item => {
              return item["id"];
            })
          })
          .then(response => {
            this.loading = false
          })
          .catch(error => {
             this.$swal(this.$t('Error'), error, 'error')
          });
      },

      itemKey(item) {
        if (!this.itemKeys.has(item))
          this.itemKeys.set(item, ++this.currentItemKey)
        return this.itemKeys.get(item)
      },

      editItem(item) {
        this.editedIndex = this.items.indexOf(item)

        if(item.images.length) {
          item.images.forEach((image) => {
              if(image.dropzone) {
                let file = { size: image.size, name: image.url }
                let url = '/photos/upload/' + image.url
                this.$refs.myVueDropzone.manuallyAddFile(file, url)
              }
          })
        }
        this.editedItem = Object.assign({}, item)
        this.dialog = true;
      },

      deleteItem(item) {
        this.$swal({
          title: this.$t("Are you sure?"),
          text: this.$t("You won't be able to revert this!"),
          type: "warning",
          showCancelButton: true,
          cancelButtonColor: "#3085d6",
          confirmButtonColor: "#d33",
          confirmButtonText: this.$t("Yes, delete it!"),
          cancelButtonText: this.$t("No")
        })
        .then(result => {
          if(result.value) {
            this.loading = true
            const index = this.items.indexOf(item)
            axios
              .delete("/api/pages/" + item.id)
              .then(response => {
                this.items.splice(index, 1)
                this.snackbar = true
                this.snackbarText = this.$t("Page has been deleted.")
              })
              .catch(error => {
                this.$swal(this.$t('Error'), error, 'error')
              })
              .finally(() => this.loading = false)
            }
         });
      },

      updateVisibility(item) {
          this.loading = true

          axios
            .put("/api/pages/" + item.id, {
              title: item.title,
              content: item.content,
              nav: item.nav,
              id: item.id
            })
            .then(response => {
              this.snackbar = true
              this.snackbarText = this.$t("Page has been updated.")
            })
            .catch(error => {
              const message = error.response.data.errors.title[0] || this.$t('Try again later!')
              this.$swal(this.$t('Error!'), message, 'error')
            })
            .finally(() => this.loading = false)
      },

      openDialog() {
        this.dialog = true
        this.editedItem.formToken = Math.random().toString(36).substr(2).toUpperCase()
      },

      closeDialog() {  
        this.dialog = false
        this.clearDialog()
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      clearDialog() {
        this.editedItem.title = ''
        this.editedItem.content = ''
        document.querySelector('#dropzone').classList.remove('dz-started')
        document.querySelectorAll('.dz-preview').forEach(e => e.parentNode.removeChild(e))
      },

      save() {
        if(this.valid) {
        this.loading = true
        if(this.editedIndex > -1) {
          axios
            .put("/api/pages/" + this.editedItem.id, {
              title: this.editedItem.title,
              content: this.editedItem.content,
              id: this.editedItem.id
            })
            .then(response => {
              Object.assign(this.items[this.editedIndex], response.data)
              this.clearDialog()
              this.snackbar = true
              this.snackbarText = this.$t("Page has been updated.")
              this.closeDialog()
            })
            .catch(error => {
              const message = error.response.data.errors.title[0] || this.$t('Try again later!')
              this.$swal(this.$t('Error!'), message, 'error')
            })
            .finally(() => this.loading = false)
        } else {
          axios
            .post("/api/pages", {
              title: this.editedItem.title,
              content: this.editedItem.content,
              form_token: this.editedItem.formToken
            })
            .then(response => {
              this.items.push(response.data)
              this.clearDialog()
              this.snackbar = true
              this.snackbarText = this.$t("Page has been created.")
              this.closeDialog()
            })
            .catch(error => {
              const message = error.response.data.errors.title[0] || this.$t('Try again later!')
              this.$swal(this.$t('Error!'), message, 'error')
            })
            .finally(() => this.loading = false)
          }
        }
       }
     }
  }
</script>