<script>
export default {
  methods: {
    loadRegionsList: function(self) {
      if (self.user.country === "") {
        self.showRegion = false;
        self.showCity = false;
        self.user.region = "";
        self.user.city = "";
      } else {
        self.regionOptions = self.locations[self.user.country]["region"];
        self.showRegion = true;
        self.user.region = self.regionOptions[0];
        this.loadCitiesList(self);
      }
    },
    loadCitiesList(self) {
      self.showCity = true;
      self.cityOptions =
        self.locations[self.user.country][self.user.region]["city"];
      self.user.city = self.cityOptions[0];
    },
  },
};
</script>
