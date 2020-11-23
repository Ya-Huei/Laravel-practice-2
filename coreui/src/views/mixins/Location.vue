<script>
export default {
  methods: {
    loadRegionsList: function(self) {
      if (self.location.country === "") {
        self.showRegion = false;
        self.showCity = false;
        self.location.region = "";
        self.location.city = "";
      } else {
        self.regionOptions = self.locations[self.location.country]["region"];
        self.showRegion = true;
        self.location.region = self.regionOptions[0];
        this.loadCitiesList(self);
      }
    },
    loadCitiesList(self) {
      self.showCity = true;
      self.cityOptions =
        self.locations[self.location.country][self.location.region]["city"];
      self.location.city = self.cityOptions[0];
    },
  },
};
</script>
