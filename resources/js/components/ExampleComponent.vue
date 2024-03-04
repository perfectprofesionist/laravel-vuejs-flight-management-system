<template>
  
    <div>
        <h2 class="text-center">Flight Booking</h2>
        <div class="container">
          <div class="row justify-content-center" style="text-align: center;">
          <div class="col-md-8">
            <form method="post">
            <label>
              Origin:
              <input
                type="text"
                v-model="formData.origin"
                class="form-control"
              />
            </label>
            <label>
              Destination:
              <input
                type="text"
                v-model="formData.destination"
                class="form-control"
              />
            </label>
            <label>
              Departure Date:
              <input
                type="date"
                v-model="formData.departureDate"
                class="form-control"
              />
            </label>
            <label>
              Adults:
              <input
                type="number"
                v-model="formData.adults"
                value="1"
              />
            </label><br><br>
            <button type="button" class="btn btn-outline-info"  @click="fetchData()">
              Search Flights
            </button>
          </form>
          </div>
        </div>
      </div>
        <table v-if="data && !showItinerariesState && !showBookingForm && !showThankyou" class="table"  style="width: 95%; margin: auto;">
            <thead>
                    <tr >
                      <th>ID</th>
                      <th>Price</th>
                      <th>Currency</th>
                      <th>FOR</th>
                      <th>Avail Seats</th>
                      <th>Last Ticketing Date</th>
                      <th>Duration</th>
                      <th>Itineary</th>
                      <th>Action</th>
                    </tr>
            </thead>
            <tbody style="">
            <tr v-for="element in data" :key="element.id">
                      <td >{{element.id}}</td>
                      <td > {{element.price.total}}</td>
                      <td > {{element.price.currency}}</td>
                      <td > {{element.travelerPricings.length > 0 && element.travelerPricings[0].travelerType}}</td>
                      <td > {{element.numberOfBookableSeats}}</td>
                      <td > {{element.lastTicketingDate}} </td>
                      <td > {{parseDuration(element.itineraries[0].duration)}} </td>
                      <td >
                         <button type="button" class="btn btn-primary" @click="showItinerariesTable(element)">
                          Show Itineary<span class="badge badge-light">{{element.itineraries[0].segments.length}}</span>
                         </button>
                      </td>
                      <td>
                          <div class="btn-group" role="group">
                              <button class="btn btn-outline-primary" @click="showBookingFormTable(item)">Book Flight</button>
                          </div>
                      </td>
            </tr>
            </tbody>
        </table>
        <div v-if="showItinerariesState">
          <div class="row justify-content-end" style="width: 98.8%;">
              <div class="col-auto">
                <button class="btn btn-danger" style="height: 75%;font-size: 16px;text-align: center;" @click="closeItineraries">X</button>
              </div>
            </div>
          <table className="w-full" v-if="showItinerariesState" style="width: 95%; margin: auto;">
          <!-- <p><button class="btn btn-outline-danger">X</button></p> -->
          
                  <thead>
                    <tr className="text-md font-semibold tracking-wide text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                      <!-- <th className="px-4 py-3 border">ID</th> -->
                      <th className="px-4 py-3 border">Departure </th>
                      <th className="px-4 py-3 border">Departure At</th>
                      <th className="px-4 py-3 border">Arrival</th>
                      <th className="px-4 py-3 border">Arrival AT</th>
                      <th className="px-4 py-3 border">Duration</th>
                    </tr>
                  </thead>
                  <tbody className="bg-white">  
                    <tr v-for="element in showItineraries.itineraries[0].segments" :key="element.id">
                              <!-- <th className="px-4 py-3 border">{index+1}</th> -->
                              <th className="px-4 py-3 border">{{element.departure.iataCode}}</th>
                              <th className="px-4 py-3 border">{{element.departure.at.split('T')[0]}}</th>
                              <th className="px-4 py-3 border">{{element.arrival.iataCode}}</th>
                              <th className="px-4 py-3 border">{{element.arrival.at.split('T')[0]}}</th>
                              <th className="px-4 py-3 border">{{parseDuration(element.duration)}}</th>
                            </tr>
                    <tr>
                    <th colSpan='3' className="px-4 py-3 border"> Stay Time:- {{ stayTime }}</th>
                        <th colSpan='3' className="px-4 py-3 border"> Total Time:- {{parseDuration(showItineraries.itineraries[0].duration)}} </th>
                    </tr>
                  </tbody>
        </table></div>
        
    </div>
    <div v-if="isLoading" class="loader">
    Loading...
  </div>
  <div v-if="showThankyou">
    <h1>Thankyou Booking Booking with us</h1>
    <button class="btn btn-primary" @click="hideThankyouTable">Book Again</button>
  </div>
  <div v-if="showBookingForm" >
    <h3 align="center">Booking Details</h3>
    <div class="row justify-content-end" style="width: 98.8%;">
      <div class="col-auto">
         <button class="btn btn-danger" style="height: 75%;font-size: 16px;text-align: center;" @click="closeBookingform">X</button>
      </div>
    </div>
    <table >
          <thead>
            <tr>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Date Of Birth</th>
              <th>Email</th>
              <th>Phone Number</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th className="px-4 py-3 border">
                <input className='border' name="firstName" type="text"  required/>
              </th>
              <th className="px-4 py-3 border">
              <input className='border' name="lastName" type="text"  required/>
              </th>
              <th className="px-4 py-3 border">
                <input type="date" class="form-control"/>
              </th>
              <th className="px-4 py-3 border">
              <input  name="email"  type="email" required/>
              </th>
              <th className="px-4 py-3 border">
              <input type="phone" name="phoneNumber" required/>
              </th>
            </tr>
            <tr>
              <th colSpan={5}>
                <button class='btn btn-primary' @click="thankyouPage">
                  Book Flight
                </button>
              </th>
            </tr>
          </tbody>
    </table>
   </div>
</template>

<script>
import axios from 'axios';

    export default {

    data() {
      return {
        data: null,error: null,formData: {origin: 'SYD',destination: 'BKK',departureDate: '2024-04-05',adults: 1},dictionaries:null,isModalVisible: false,isLoading: false,showItineraries: null,showItinerariesState: false,stayTime: null,showBookingForm: false,showThankyou: false,closBooking: false
      };
    },
   mounted() {
   },
  methods: {
    //Method used to hide the thankyou popup
    hideThankyouTable(){
      this.showThankyou= false;
    },
    //Method used to hide the Booking Form popup
    closeBookingform(){
      this.showBookingForm = false;
    },
    //Method used to Show the Thankyou Form popup
    thankyouPage(){
    this.showThankyou = true;
    this.showBookingForm = false;
  },
  //Method used to Get thye flight api data
    async fetchData() {
      this.isLoading = true;
      try{
        let formData1 = new FormData();
        for(const key in this.formData){
          // console.log(key,this.formData[key]);
          formData1.append(key,this.formData[key]);
        }
        // console.log(formData1);
        const response = await axios.get('http://127.0.0.1:8000/api-get-flight-data',{params: this.formData});

        // console.log(response.data.data.data);
        this.data = response.data.data.data;
        this.dictionaries = response.data.data.dictionaries;
        this.isLoading = false;
      }catch(error){
        console.error('Error Fetching Data:',error);
        this.error = 'An Error occurred While Fetching Data';
      }
      // this.isLoading = true;
      //Method used to Show the Booking Form popup
    },showBookingFormTable(flightData){
      this.showBookingForm = true;
    },
    //Method used to hide the Flight Itinararies Form popup
    closeItineraries(){
      this.showItineraries = null;
      this.showItinerariesState = false;
      this.stayTime = null;
    },
    //Method used to show the Flight Itinararies Form popup
    showItinerariesTable(itineraryData){
      this.showItineraries = itineraryData;
      this.showItinerariesState = true;
      let duration  = [];
      const durations = this.showItineraries.itineraries[0].segments.map(itinerary => this.parseDuration( itinerary.duration));
      this.stayTime = this.subtractDuration(this.parseDuration(itineraryData.itineraries[0].duration),this.sumDuration(durations));
     
    },
    //Method used to Calculate the Stay Time of the flight
    subtractDuration(totalDuration, durationToSubtract) {
    // Parse total duration
    const totalParts = totalDuration.split(' ');
    const totalHours = parseInt(totalParts[0]);
    const totalMinutes = parseInt(totalParts[2]);

    // Parse duration to subtract
    const parts = durationToSubtract.split(' ');
    const hours = parseInt(parts[0]);
    const minutes = parseInt(parts[2]);

    // Perform subtraction
    let resultHours = totalHours - hours;
    let resultMinutes = totalMinutes - minutes;

    // Adjust if resultMinutes is negative
    if (resultMinutes < 0) {
        resultHours -= 1;
        resultMinutes += 60;
    }
    return resultHours+' Hours ' + resultMinutes + ' Minutes';
    // return { hours: resultHours, minutes: resultMinutes };
    },
    //Method used to Show the flight Duration
    parseDuration(durationString){
      const regex = /PT(\d+)H(\d+)M/;
    let match = durationString.match(regex);

    if (!match) {
      const regex1 = /PT(\d+)H/;
      match = durationString.match(regex1);
    }

    if (match) {
      const hours = parseInt(match[1], 10);
      const minutes = parseInt(match[2], 10);

      if (!Number.isNaN(minutes)) {
        return hours + ' Hours ' + minutes + ' Minutes';
      } else {
        return hours + ' Hours 0 Minutes';
      }
    } else {
      console.error('Invalid duration format');
      return null;
    }
    },
    //Method used to Calculate the Total Flight durations in a Itinararies
    sumDuration(durationStrings) {
    let totalHours = 0;
    let totalMinutes = 0;

    durationStrings.forEach(durationString => {
        const parts = durationString.split(' ');

        if (parts.length === 4 && parts[1].toLowerCase() === 'hours' && parts[3].toLowerCase() === 'minutes') {
            const hours = parseInt(parts[0]);
            const minutes = parseInt(parts[2]);
            totalHours += hours;
            totalMinutes += minutes;
        } else {
            console.error('Invalid duration format:', durationString);
        }
    });

    // Convert excess minutes to hours if more than 60 minutes
    totalHours += Math.floor(totalMinutes / 60);
    totalMinutes %= 60;
    return totalHours+' Hours '+totalMinutes+' Minutes';
    }
  }
}
</script>
<style scoped>
.loader {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px; /* Set to desired height */
}
td{
  text-align: center;
}
th{
  text-align: center;
}
</style>


