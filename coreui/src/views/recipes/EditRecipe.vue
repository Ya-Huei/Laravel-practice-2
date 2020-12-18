<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>{{ $t("editRecipes.title") }}</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert v-for="message in messages" :key="message" color="danger">
              {{ message }}
            </CAlert>
          </span>
          <CForm>
            <CInput
              :description="$t('editRecipes.inputNameDescription')"
              :label="$t('editRecipes.inputName')"
              v-model="name"
            />
            <div class="overflow-auto">
              <table class="table table-borderless">
                <tr class="text-center border-bottom">
                  <th colspan="4">
                    <h5>{{ $t("editRecipes.mainActive") }}</h5>
                  </th>
                  <th colspan="4">
                    <h5>{{ $t("editRecipes.attachActive") }}</h5>
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
                  <td style="min-width: 80px;">
                    {{ $t("editRecipes.step") }}{{ `${index + 1}` }}
                  </td>
                  <td style="min-width: 150px;">
                    <CSelect
                      :options="optionSteps"
                      :value.sync="recipe.step"
                      @change="loadDetail(index)"
                    />
                  </td>
                  <td class="border-right" style="min-width: 150px;">
                    <CInput
                      v-if="recipe.isShowPara"
                      type="number"
                      v-model="recipe.para"
                      appendHtml=""
                      @change="check(index)"
                    >
                      <template #append-content>{{
                        $t("createRecipes." + recipe.unit)
                      }}</template>
                    </CInput>
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
                      @change="check(index)"
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
                      @change="check(index)"
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
                      @change="check(index)"
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
              >+ {{ $t("editRecipes.addStep") }}</CButton
            >
          </CRow>
        </CCardBody>
        <CCardFooter class="d-flex justify-content-end">
          <CButton
            :disabled="!isEditedRecipe"
            color="primary"
            @click="update()"
          >
            <span v-if="isEditedRecipe"><CIcon name="cil-pencil"/></span>
            <CSpinner v-if="!isEditedRecipe" color="info" size="sm" />
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
    check(index) {
      let self = this;
      self.recipes[index].act1 = self.checkValue(
        self.recipes[index].act1,
        null
      );
      self.recipes[index].act2 = self.checkValue(
        self.recipes[index].act2,
        null
      );
      self.recipes[index].act3 = self.checkValue(
        self.recipes[index].act3,
        null
      );
      self.recipes[index].para = self.checkValue(
        self.recipes[index].para,
        self.recipes[index].unit
      );
    },
    checkValue(value, unit) {
      var regex = /^(0|\+?[1-9][0-9]*)$/;
      value = !regex.test(value) || value < 0 ? 0 : value;
      if (unit === null) {
        value = value > 7200 ? 7200 : value;
        return value;
      }

      switch (unit) {
        case "L":
          value = value > 24 ? 24 : value;
          break;
        case "℃":
        case "%":
          value = value > 100 ? 100 : value;
          break;
        case "sec":
          value = value > 7200 ? 7200 : value;
          break;
      }
      value = value > 7200 ? 7200 : value;
      return value;
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },
    resetRecipe(index) {
      let self = this;
      self.recipes[index].para = "";
      self.recipes[index].act1 = "0";
      self.recipes[index].act2 = "0";
      self.recipes[index].act3 = "0";
      self.recipes[index].unit = "";
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
          self.recipes[recipesIndex].act1 = "0";
          self.recipes[recipesIndex].act2 = "0";
          self.recipes[recipesIndex].act3 = "0";
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
            recipeName: self.name,
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
                self.recipes[count].act1 = item;
                break;
              case 3: // act2
                self.recipes[count].act2 = item;
                break;
              case 4: // act3
                self.recipes[count].act3 = item;
                break;
            }
          });
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
