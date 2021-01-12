
<template>
  <div class="container">

    <div class="row justify-content-center">

      <div class="col-md-12">
        <h4>Crud de productos</h4>
        <div class="card">



          <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                  <form class="form-inline my-2 mylg-0">
                      <input type="search" class="form-control" v-model="buscador" placeholder="Buscar por nombre.." @keyup="buscarProducto">
                  </form>
            </div>
            <div class="col-md-2" >
                <button @click="agregarProductoModal" class="btn btn-primary form-inline my-2 mylg-0">
                    Agregar producto
                </button>
            </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover table-sm">
                <tbody>
                  <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Opc.</th>
                  </tr>

                  <tr v-for="item in tabledata.data" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.codigo }}</td>
                    <td>{{ item.nombre }}</td>
                    <td>{{ item.descripcion }}</td>
                    <td>
                      <img
                        :src="`img/${item.imagen}`"
                        class="profile-user-img img-fluid img-circle"
                        style="height: 50px; width: 50px; border-radius:5px; object-fit:cover"
                      />
                    </td>

                    <td>
                      <a href="#" @click="editProductoModal(item)">
                        <i class="fa fa-edit blue"></i>
                      </a>
                      |
                      <a href="#" @click="deletePhoto(item.id)">
                        <i class="fa fa-trash red" style="color: #e3342f"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
              <pagination
                :data="tabledata"
                @pagination-change-page="getResults"
              ></pagination>
            </div>

            <div
              class="modal fade"
              id="agregarProducto"
              tabindex="-1"
              role="dialog"
              aria-labelledby="agregarProductoLabel"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="agregarProductoLabel">
                      Agregar producto
                    </h5>

                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form class="form-horizontal">
                    <div class="modal-body">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"
                          >Código del producto</label
                        >
                        <div class="col-sm-4">
                          <input
                            type="text"
                            v-model="form.codigo"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors.has('codigo') }"
                          />
                          <has-error :form="form" field="codigo"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"
                          >Nombre del producto</label
                        >
                        <div class="col-sm-9">
                          <input
                            type="text"
                            v-model="form.nombre"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors.has('nombre') }"
                          />
                          <has-error :form="form" field="nombre"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"
                          >Descripción del producto</label
                        >
                        <div class="col-sm-9">
                          <textarea
                            v-model="form.descripcion"
                            class="form-control form-control-sm"
                            cols="30"
                            rows="3"
                            :class="{
                              'is-invalid': form.errors.has('descripcion'),
                            }"
                          ></textarea>
                          <has-error
                            :form="form"
                            field="descripcion"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-3 col-form-label"
                          >Foto del producto</label
                        >
                        <div class="col-sm-9">
                          <input
                            type="file"
                            @change="uploadProducto"
                            style="border: none"
                            name="imagen"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('imagen') }"
                          />
                          <has-error :form="form" field="imagen"></has-error>
                        </div>
                      </div>
                      <div class="card card-primary card-outline">
                        <div class="text-center">
                          <img
                            style="width: 120px; object-fit: cover"
                            class="profile-user-img img-fluid img-circle"
                            :src="getPhoto()"
                            alt="Foto del producto"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button
                        type="button"
                        class="btn btn-danger"
                        data-dismiss="modal"
                      >
                        Cerrar
                      </button>
                      <button
                        type="submit"
                        @click.prevent="agregarProducto"
                        class="btn btn-primary"
                      >
                        Agregar producto
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div
              class="modal fade"
              id="addNew"
              tabindex="-1"
              role="dialog"
              aria-labelledby="addNewLabel"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">
                      Actualizar producto
                    </h5>

                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form @submit.prevent="UpdateProducto">
                    <div class="modal-body">
                      <div class="form-group row">
                          <label for="inputName" class="col-sm-3 col-form-label"
                          >Código del producto</label
                        >
                        <div class="col-sm-4">
                            <input
                            v-model="form.codigo"
                            type="text"
                            name="codigo"
                            placeholder="Código"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors.has('codigo') }"
                            />
                        </div>
                        <has-error :form="form" field="codigo"></has-error>
                      </div>

                      <div class="form-group row">
                          <label for="inputName" class="col-sm-3 col-form-label"
                          >Nombre del producto</label
                        >
                        <div class="col-sm-9">
                            <input
                            v-model="form.nombre"
                            type="text"
                            name="nombre"
                            placeholder="Nombre del producto"
                            class="form-control form-control-sm"
                            :class="{ 'is-invalid': form.errors.has('nombre') }"
                            />
                            <has-error :form="form" field="nombre"></has-error>
                        </div>
                      </div>


                      <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label"
                          >Descripción del producto</label
                        >
                        <div class="col-sm-9">
                          <textarea
                            v-model="form.descripcion"
                            class="form-control form-control-sm"
                            cols="30"
                            rows="3"
                            :class="{
                              'is-invalid': form.errors.has('descripcion'),
                            }"
                          ></textarea>
                          <has-error
                            :form="form"
                            field="descripcion"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-3 col-form-label"
                          >Foto del producto</label
                        >
                        <div class="col-sm-9">
                          <input
                            type="file"
                            @change="uploadProducto"
                            style="border: none"
                            name="imagen"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('imagen') }"
                          />
                          <has-error :form="form" field="imagen"></has-error>
                        </div>
                      </div>
                      <div class="card card-primary card-outline">
                        <div class="text-center">
                          <img
                            style="width: 120px; object-fit: cover"
                            class="profile-user-img img-fluid img-circle"
                            :src="getPhoto()"
                            alt="Foto del producto"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button
                        type="button"
                        class="btn btn-danger"
                        data-dismiss="modal"
                      >
                        Cerrar
                      </button>
                      <button type="submit" class="btn btn-primary">
                        Actualizar
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tabledata: {},
      buscador:"",
      setTimeOutBuscador:"",
      form: new Form({
        id: "",
        codigo: "",
        nombre: "",
        descripcion: "",
        imagen: "",
      }),
    };
  },
  methods: {
    uploadProducto(e) {
      let file = e.target.files[0];
      let reader = new FileReader();

      if (file["size"] < 2111775) {
        reader.onloadend = (file) => {
          this.form.imagen = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        alert("La imagen no debe exeder los 2 MB");
      }
    },

    getPhoto() {
      let imagen =
        this.form.imagen.length > 100
          ? this.form.imagen
          : "img/" + this.form.imagen;
      return imagen;
    },


    //Insert Producto
    agregarProducto() {
      this.form
        .post("api/producto")
        .then(() => {
          Toast.fire({
            icon: "success",
            title: "Producto agregado exitosamente",
          });
          this.form.reset();
          this.loadTableData();
          $("#agregarProducto").modal("toogle");
        })
        .catch(() => {
          console.log("Algo salio mal");
        });
    },

    buscarProducto(){
        var objeto = {};
        objeto['buscador'] = this.buscador;
        clearTimeout(this.setTimeOutBuscador)
        this.setTimeOutBuscador = setTimeout(() => {
            // console.log(this.buscador);
            axios
                .post("api/buscarProducto", objeto)
                .then(({ data }) => (this.tabledata = data))
                .catch(() => {
                console.log("Error...");
                });
        }, 360);
    },


    //get Table data
    loadTableData() {
      axios
        .get("api/producto")
        .then(({ data }) => (this.tabledata = data))
        .catch(() => {
          console.log("Error...");
        });
    },

    //Pagination
    getResults(page = 1) {
      axios.get("api/producto?page=" + page).then((response) => {
        this.tabledata = response.data;
      });
    },

    //Edit Option
    editProductoModal(item) {
      this.form.clear();
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(item);
    },

    agregarProductoModal() {
      // this.form.clear();
      this.form.reset();
      $("#agregarProducto").modal("show");
      this.form.fill(item);
    },

    UpdateProducto() {
      this.form
        .put("api/producto/" + this.form.id)
        .then(() => {
          Toast.fire({
            icon: "success",
            title: "Producto actualizado exitosamente",
          });

          this.loadTableData();
          this.form.reset();

          $("#addNew").modal("hide");
        })
        .catch(() => {
          console.log("Error.....");
        });
    },

    //Delete producto
    deletePhoto(id) {
      Swal.fire({
        title: "Estas seguro?",
        text: "No podras revertir este proceso!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, deseo hacerlo!",
      }).then((result) => {
        if (result.value) {
          //Send Request to server
          this.form
            .delete("api/producto/" + id)
            .then((response) => {
              Swal.fire(
                "Eliminado!",
                "Pproducto eliminado exitosamente",
                "success"
              );
              this.loadTableData();
            })
            .catch(() => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Algo salio mal!",
                //   footer: '<a href>Why do I have this issue?</a>'
              });
            });
        }
      });
    },
  },

  created() {
    //LoadTableData
    this.loadTableData();
  },
};
</script>
