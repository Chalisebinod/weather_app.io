Okeys("Solihull");
const button = document.getElementById("search-button");
const inputValue = document.getElementById("search-box");
const cityName = document.getElementById("location");
const description = document.getElementById("cloud");
const temperat = document.getElementById("temp");
const icon = document.getElementById("icon");
const humidity = document.getElementById("humidity");
const day = document.getElementById("day");
const date = document.getElementById("date");
const wind = document.getElementById("wind");
const feels = document.getElementById("feel");
const pressure = document.getElementById("pressure");

button.addEventListener('click', ok)

function ok(){
    Okeys(inputValue.value)
}

function Okeys(name){
  fetch('https://api.openweathermap.org/data/2.5/weather?q='+name+'&units=metric&appid=fee0c52216fcf74e6a02307994b0d6aa')
  .then(response => response.json())
  .then(data => {
    console.log("Data from the api");
    let lat=data["coord"]["lat"]
    let lon=data["coord"]["lon"]
    stringconv=JSON.stringify(data);
    localStorage.setItem(name.toLowerCase(),stringconv)
    fetch('https://api.openweathermap.org/data/2.5/weather?lat='+lat+'&lon='+lon+'&appid=fee0c52216fcf74e6a02307994b0d6aa')
    .then(res=>res.json())
    .then(dat=>{
        console.log("data is",dat)
        let rain=dat["rain"]["1h"];
        document.getElementById("rain").innerHTML=rain+"mm";
    })
    .catch(error=>document.getElementById("rain").innerHTML="N/A")
   
    console.log(data)
    var humidity=data['main']['humidity']
    var windS = data['wind']['speed'];
    var nameValue = data['name'];
    var tempS = data['main']['temp'];
    var descS = data['weather'][0]['description']
    var iconS = data['weather'][0]['icon']
    var feelS = data['main']['feels_like'];
    var pressureS=data["main"]["pressure"]
    pressure.innerHTML=pressureS+"mb";
    cityName.innerHTML = nameValue;
    temperat.innerHTML = tempS+"°C";
    description.innerHTML = descS;
    humidity.innerHTML="Humidity: "+humidity+"%";
    wind.innerHTML = "Wind: "+windS + "m/s";
    feels.innerHTML="Feels Like: "+feelS+"°C";
    let currentDate = new Date().toJSON().slice(0, 10);
    console.log(currentDate); // "2022-06-17"
    const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    const d = new Date();
    let dayss = weekday[d.getDay()];
    console.log(dayss)
    day.innerHTML=dayss;
    date.innerHTML=currentDate;
    icon.src = 'http://openweathermap.org/img/w/'+ iconS +'.png';
  })
  .catch(err => {
    alert("Invalid Location! or No internet")
    console.log("Data from local storage");
    localdata=localStorage.getItem(name.toLowerCase());
    fdata=JSON.parse(localdata);
    console.log("local storage ko data:",fdata)
    var humidity=fdata['main']['humidity']
    var windS = fdata['wind']['speed'];
    var nameValue = fdata['name'];
    var tempS = fdata['main']['temp'];
    var descS = fdata['weather'][0]['description']
    var feelS = fdata['main']['feels_like'];
    var pressureS=fdata["main"]["pressure"]
    pressure.innerHTML=pressureS+"mb";
    cityName.innerHTML = nameValue;
    temperat.innerHTML = tempS+"°C";
    description.innerHTML = descS;
    humidity.innerHTML="Humidity: "+humidity+"%";
    wind.innerHTML = "Wind: "+windS + "m/s";
    feels.innerHTML="Feels Like: "+feelS+"°C";
    let currentDate = new Date().toJSON().slice(0, 10);
    console.log(currentDate); // "2022-06-17"
    const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    const d = new Date();
    let dayss = weekday[d.getDay()];
    console.log(dayss)
    day.innerHTML=dayss;
    date.innerHTML=currentDate;
    icon.alt="No image in offline mode"
})
}