<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="6">
                <h4>Recipes</h4>
              </CCol>
              <CCol col="6" class="d-flex justify-content-end">
                <CButton color="primary" @click="createRecipe()"
                  >Create Recipe</CButton
                >
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="6"
              pagination
            >
              <template #operate="{item}">
                <td>
                  <CButton color="primary" @click="editRecipe(item.id)"
                    >Edit</CButton
                  >
                  <CButton color="primary" @click="showRecipe(item.id)"
                    >Detail</CButton
                  >
                  <CButton
                    color="success"
                    @click="exportRecipe(item.recipe, item.name)"
                    >Export</CButton
                  >
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";

export default {
  name: "Recipes",
  data: () => {
    return {
      items: [],
      fields: ["id", "firm", "name", "registered", "updated", "operate"],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
    };
  },
  methods: {
    exportRecipe(data, name) {
      let fileName = name + "_recipe.csv";
      let blob = new Blob([data], {
        type: "application/octet-stream",
      });
      var href = URL.createObjectURL(blob);
      var link = document.createElement("a");
      document.body.appendChild(link);
      link.href = href;
      link.download = fileName;
      link.click();
    },
    editLink(id) {
      return `recipes/${id.toString()}/edit`;
    },
    showLink(id) {
      return `recipes/${id.toString()}/show`;
    },
    showRecipe(id) {
      const showLink = this.showLink(id);
      this.$router.push({ path: showLink });
    },
    editRecipe(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
    createRecipe() {
      this.$router.push({ path: `recipes/create` });
    },
    getDevices() {
      let self = this;
      axios
        .get("/api/recipes?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.items = response.data;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getDevices();
  },
};
</script>
