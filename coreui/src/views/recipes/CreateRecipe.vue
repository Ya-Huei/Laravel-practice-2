<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Create Recipe</h4>
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
            <div class="overflow-auto">
              <table class="table table-borderless">
                <tr class="text-center border-bottom">
                  <th colspan="4">
                    <h5>Main Active</h5>
                  </th>
                  <th colspan="4">
                    <h5>Attach Active</h5>
                  </th>
                </tr>
                <tr v-for="(recipe, index) in recipes">
                  <td
                    style="width: 83px;"
                    :class="{
                      'text-right': index === 0,
                    }"
                  >
                    <CButtonGroup>
                      <CButton
                        v-if="isShowSub && index !== 0"
                        color="info"
                        variant="outline"
                        @click="up(index)"
                        ><CIcon name="cil-arrow-top"
                      /></CButton>
                      <CButton
                        v-if="isShowSub && index < recipes.length - 1"
                        color="info"
                        variant="outline"
                        @click="down(index)"
                        ><CIcon name="cil-arrow-bottom"
                      /></CButton>
                    </CButtonGroup>
                  </td>
                  <td style="min-width: 60px;">
                    {{ `Step${index + 1}` }}
                  </td>
                  <td style="min-width: 150px;">
                    <CSelect
                      :options="optionSteps"
                      :value.sync="recipe.step"
                      @change="loadDetail(index)"
                    />
                  </td>
                  <td class="border-right" style="min-width: 150px;">
                    <div class="input-group" v-if="recipe.isShowPara">
                      <input
                        type="number"
                        class="form-control"
                        v-model="recipe.para"
                        :requeired="`${recipe.isShowPara}`"
                      />
                      <div class="input-group-append">
                        <span
                          class="input-group-text align-center"
                          style="min-width: 50px;"
                          >{{ recipe.unit }}</span
                        >
                      </div>
                    </div>
                    <CSelect
                      :options="[
                        { value: '0', label: 'stir after' },
                        { value: '1', label: 'stir before' },
                      ]"
                      :value.sync="recipe.para"
                      v-if="recipe.isShowParaSelect"
                    />
                  </td>
                  <td style="min-width: 120px;">
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
                  </td>
                  <td style="min-width: 120px;">
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
                  </td>
                  <td style="min-width: 120px;">
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
                  </td>
                  <td style="min-width: 100px;">
                    <CButton
                      v-if="isShowSub"
                      color="danger"
                      variant="outline"
                      @click="sub(index)"
                      ><CIcon name="cil-minus"
                    /></CButton>
                  </td>
                </tr>
              </table>
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
            :disabled="!isCreatedRecipe"
            color="primary"
            @click="store()"
          >
            <span v-if="isCreatedRecipe"><CIcon name="cil-save"/></span>
            <CSpinner v-if="!isCreatedRecipe" color="info" size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack"
            ><CIcon name="cil-action-undo"
          /></CButton>
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
  name: "CreateRecipe",
  data: () => {
    return {
      name: "",
      recipes: [
        {
          step: "3",
          para: "",
          act1: "0",
          act2: "0",
          act3: "0",
          unit: "L",
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
      isCreatedRecipe: true,
      showMessage: false,
    };
  },
  methods: {
    up(index) {
      let self = this;
      const arrayMove = require("array-move");
      if (index - 1 < 0) {
        return false;
      }
      self.recipes = arrayMove(self.recipes, index, index - 1);
    },
    down(index) {
      let self = this;
      const arrayMove = require("array-move");
      if (index + 1 > self.recipes.length - 1) {
        return false;
      }
      self.recipes = arrayMove(self.recipes, index, index + 1);
    },
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
        step: "0",
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
    store() {
      let self = this;
      self.isCreatedRecipe = false;
      axios
        .post("/api/recipes?token=" + localStorage.getItem("api_token"), {
          name: self.name,
          recipes: self.recipes,
        })
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isCreatedRecipe = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getInfo() {
      let self = this;
      axios
        .get("/api/recipes/create?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.optionSteps = response.data.steps;
          self.optionActs = response.data.actions;
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
