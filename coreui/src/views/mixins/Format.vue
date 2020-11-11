<script>
export default {
  methods: {
    formResponseFormat: function(error) {
      let messages = [];
      if(typeof error.response.data.errors == 'undefined'){
        messages.push("error");
        return messages;
      }
      for (const [key, value] of Object.entries(error.response.data.errors)){
        messages.push(value[0]);
      }
      return messages;
    }
  }
};
</script>