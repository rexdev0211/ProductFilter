<template>
  <p>You are welcome!</p>
</template>

<script>
// import Timer from "easytimer.js";
export default {
  name: "dashboard-index",
  
  data() {
    return {
      latestInterval: {},
      secondsLeftToday: 0,
      left: {
        hours: 0,
        minutes: 0,
        seconds: 0,
      },
      seasons: ["Winter", "Spring", "Summer", "Fall"],
      newInterval: {},
      editedInterval: {},
      deletedInterval: {},
      showEditIntervalModal: false,
      showDeleteIntervalModal: false,
      showChart: false,
      currentDate: this.$date(),
    };
  },
};
</script>

<style scoped>
