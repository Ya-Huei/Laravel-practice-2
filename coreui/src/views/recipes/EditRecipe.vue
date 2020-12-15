<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit Recipe</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert v-for="message in messages" :key="message" color="danger">
              {{ message }}
            </CAlert>
          </span>
          <CForm>
            <CInput
              description="Enter your recipe name"
              label="Name"
              v-model="name"
            />
            <div>
              <CRow>
                <CCol col="5" class="d-flex justify-content-center"
                  ><h5>Main Active</h5></CCol
                >
                <CCol col="7" class="d-flex justify-content-center"
                  ><h5>Attach Active</h5></CCol
                >
              </CRow>
            </div>
            <hr />
            <div v-for="(recipe, index) in recipes">
              <div>
                <CRow>
                  <CCol col="3">
                    <CSelect
                      :label="`Step${index + 1}`"
                      :options="optionSteps"
                      :value.sync="recipe.step"
                      @change="loadDetail(index)"
                      horizontal
                    />
                  </CCol>
                  <CCol
                    col="2"
                    style="border-right: 1px solid rgba(0, 0, 21, 0.2);"
                  >
                    <CInput
                      type="number"
                      v-model="recipe.para"
                      v-if="recipe.isShowPara"
                      :requeired="`${recipe.isShowPara}`"
                      :placeholder="`${recipe.unit}`"
                    />
                    <CSelect
                      :options="[
                        { value: '0', label: 'stir after' },
                        { value: '1', label: 'stir before' },
                      ]"
                      :value.sync="recipe.para"
                      v-if="recipe.isShowParaSelect"
                    />
                  </CCol>
                  <CCol col="2">
                    <CSelect
                      :options="optionActs"
                      :value.sync="recipe.act1"
                      v-if="recipe.isShowAct"
                    />
                    <CInput
                      type="number"
                      v-model="recipe.act1"
                      v-if="recipe.isShowActInput"
                      description="delay sec"
                    />
                  </CCol>
                  <CCol col="2">
                    <CSelect
                      :options="optionActs"
                      :value.sync="recipe.act2"
                      v-if="recipe.isShowAct"
                    />
                    <CInput
                      type="number"
                      v-model="recipe.act2"
                      v-if="recipe.isShowActInput"
                      description="stir sec"
                    />
                  </CCol>
                  <CCol col="2">
                    <CSelect
                      :options="optionActs"
                      :value.sync="recipe.act3"
                      v-if="recipe.isShowAct"
                    />
                    <CInput
                      type="number"
                      v-model="recipe.act3"
                      v-if="recipe.isShowActInput"
                      description="stop sec"
                    />
                  </CCol>
                  <CCol col="1">
                    <CButton
                      v-if="isShowSub"
                      color="danger"
                      variant="outline"
                      @click="sub(index)"
                      >-</CButton
                    >
                  </CCol>
                </CRow>
              </div>
            </div>
          </CForm>
          <CRow class="d-flex justify-content-center">
            <CButton
              color="info"
              variant="outline"
              @click="add()"
              v-if="isShowAdd"
              >+ Add Step</CButton
            >
          </CRow>
        </CCardBody>
        <CCardFooter class="d-flex justify-content-end">
          <CButton
            :disabled="!isEditedRecipe"
            color="primary"
            @click="update()"
          >
            <span v-if="isEditedRecipe">Save</span>
            <CSpinner v-if="!isEditedRecipe" color="info" size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack">Back</CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import format from "../mixins/Format.vue";
export default {
  mixins: [format],
  name: "EditRecipe",
  data: () => {
    return {
      name: "",
      recipes: [
        {
          step: "",
          para: "",
          act1: "0",
          act2: "0",
          act3: "0",
          unit: "",
          isShowPara: true,
          isShowAct: true,
          isShowActInput: false,
          isShowParaSelect: false,
        },
      ],
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionSteps: [],
      optionActs: [],
      isShowSub: false,
      count: 1,
      maxStep: 50,
      isShowAdd: true,
      isEditedRecipe: true,
      showMessage: false,
    };
  },
  methods: {
    sub(index) {
      let self = this;
      self.recipes.splice(index, 1);
      if (self.recipes.length === 1) {
        self.isShowSub = false;
      }
      self.count--;

      if (self.count < self.maxStep) {
        self.isShowAdd = true;
      }
    },
    add() {
      let self = this;
      let recipe = {
        step: "",
        para: "",
        act1: "0",
        act2: "0",
        act3: "0",
        unit: "",
        isShowPara: false,
        isShowAct: false,
        isShowActInput: false,
        isShowParaSelect: false,
      };
      self.recipes.push(recipe);
      self.isShowSub = true;
      self.count++;
      if (self.count >= self.maxStep) {
        self.isShowAdd = false;
      }
    },
    resetRecipe(index) {
      let self = this;
      self.recipes[index].para = "";
      self.recipes[index].isShowPara = true;
      self.recipes[index].isShowAct = true;
      self.recipes[index].isShowParaSelect = false;
      self.recipes[index].isShowActInput = false;
    },
    loadDetail(recipesIndex) {
      let self = this;
      let option = [];

      self.optionSteps.forEach(function(item, index, array) {
        if (item.value === self.recipes[recipesIndex].step) {
          option = item;
        }
      });

      self.resetRecipe(recipesIndex);

      self.recipes[recipesIndex].unit = option.unit;
      switch (option.value) {
        case "0":
          self.recipes[recipesIndex].isShowPara = false;
          self.recipes[recipesIndex].isShowAct = false;
          break;
        case "1":
          self.recipes[recipesIndex].isShowPara = false;
          break;
        case "100":
          self.recipes[recipesIndex].isShowPara = false;
          self.recipes[recipesIndex].isShowParaSelect = true;
          self.recipes[recipesIndex].isShowAct = false;
          self.recipes[recipesIndex].isShowActInput = true;
          self.recipes[recipesIndex].act1 = "";
          self.recipes[recipesIndex].act2 = "";
          self.recipes[recipesIndex].act3 = "";
          break;
      }
    },
    goBack() {
      this.$router.go(-1);
    },
    update() {
      let self = this;
      self.isEditedRole = false;
      axios
        .post(
          "/api/recipes/" +
            self.$route.params.id +
            "?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "PUT",
            name: self.name,
            recipes: self.recipes,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isEditedRole = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getInfo() {
      let self = this;
      axios
        .get(
          "/api/recipes/" +
            self.$route.params.id +
            "/edit?token=" +
            localStorage.getItem("api_token")
        )
        .then(function(response) {
          self.optionSteps = response.data.steps;
          self.optionActs = response.data.actions;
          self.name = response.data.recipe.name;
          var recipe = response.data.recipe.recipe.split(",");
          let count = -1;

          recipe.forEach(function(item, index, array) {
            switch (index % 5) {
              case 0: // Step
                if (index + 5 < recipe.length) {
                  self.add();
                }
                count++;
                self.recipes[count].step = item;
                self.loadDetail(count);
                break;
              case 1: // para
                self.recipes[count].para = item;
                break;
              case 2: // act1
                console.log(item);
                self.recipes[count].act1 = item;
                break;
              case 3: // act2
                console.log(item);
                self.recipes[count].act2 = item;
                break;
              case 4: // act3
                console.log(item);
                self.recipes[count].act3 = item;
                break;
            }
          });
          // self.recipe = response.data.actions;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
