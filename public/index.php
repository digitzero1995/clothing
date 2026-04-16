<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TrendAura</title>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: Arial, sans-serif;
}

/* slider */
.slider{
    position: relative;
    overflow: hidden;
}
.slide{
    display: none;
}
.slide.active{
    display:block;
}

/* chatbox */
.chatbox{
    position: fixed;
    bottom: 20px;
    right: 20px;
}
.chat-window{
    display:none;
}
</style>

</head>
<body class="bg-gray-100">

<!-- TOP BAR -->
<div class="bg-black text-white text-sm px-6 py-2 flex justify-between">
<div>
GREENCARD | GIFT CARD | STORE LOCATOR | TRACK ORDER | CONTACT
</div>
<div>
STORE MODE
</div>
</div>

<!-- NAVBAR -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">

<div class="text-3xl font-bold text-teal-500">
TrendAura
</div>

<div class="space-x-6 hidden md:flex">
<a href="category.php?cat=all" class="hover:text-teal-500">SHOP ALL</a>
<a href="category.php?cat=women" class="hover:text-teal-500">WOMEN</a>
<a href="category.php?cat=men" class="hover:text-teal-500">MEN</a>
<a href="category.php?cat=kids" class="hover:text-teal-500">KIDS</a>
<a href="category.php?cat=home" class="hover:text-teal-500">HOME & LIVING</a>
<a href="category.php?cat=brands" class="hover:text-teal-500">BRANDS</a>
<a href="category.php?cat=sale" class="hover:text-teal-500 text-red-500">SALE</a>
</div>

<div class="flex items-center space-x-4">
<form method="GET" action="search.php" class="flex items-center bg-gray-100 rounded px-3 py-1">
<input type="text" name="q" placeholder="Search..." class="bg-gray-100 border-none outline-none w-32">
<button type="submit" class="text-teal-500 ml-2"><i class="fa fa-search"></i></button>
</form>
<i class="fa fa-heart"></i>
<a href="login.php"><i class="fa fa-user"></i></a>
<a href="cart.php"><i class="fa fa-cart-shopping"></i></a>
</div>

</nav>

<!-- SLIDER -->
<div class="slider">

<div class="slide active">
<img src="assets/banner1.jpg" class="w-full h-[450px] object-cover">
</div>

<div class="slide">
<img src="assets/banner3.jpg" class="w-full h-[450px] object-cover">
</div>

<button onclick="prevSlide()" class="absolute left-4 top-1/2 bg-white px-3 py-2 rounded-full">
<i class="fa fa-arrow-left"></i>
</button>

<button onclick="nextSlide()" class="absolute right-4 top-1/2 bg-white px-3 py-2 rounded-full">
<i class="fa fa-arrow-right"></i>
</button>

</div>


<!-- CATEGORY -->
<div class="grid grid-cols-2 md:grid-cols-6 gap-4 p-6 bg-white">

<div class="text-center">
<img src="assets\woman.jpg" class="rounded-lg">
<p class="mt-2 font-semibold">WOMEN</p>
</div>

<div class="text-center">
<img src="assets\man.jpg" class="rounded-lg">
<p class="mt-2 font-semibold">MEN</p>
</div>

<div class="text-center">
<img src="assets\kids.jpg" class="rounded-lg">
<p class="mt-2 font-semibold">KIDS</p>
</div>

<div class="text-center">
<img src="assets\footware.jpg" class="rounded-lg">
<p class="mt-2 font-semibold">FOOTWEAR</p>
</div>

<div class="text-center">
<img src="assets\beauty.jpg" class="rounded-lg" >
<p class="mt-2 font-semibold">BEAUTY</p>
</div>

<div class="text-center">
<img src="assets\newseason.jpg" class="rounded-lg">
<p class="mt-2 font-semibold">NEW SEASON</p>
</div>

</div>


<!-- CURATED LOOK -->
<div class="p-8">

<h2 class="text-3xl font-bold text-center mb-6">
Curated Looks For You
</h2>

<div class="grid md:grid-cols-4 gap-6">

<div class="bg-white p-3 rounded shadow">
<img src="assets\woman.jpg">
<a href="category.php?cat=women" class="bg-teal-500 text-white px-4 py-2 mt-2 w-full rounded block text-center hover:bg-teal-600">
Shop All
</a>
</div>

<div class="bg-white p-3 rounded shadow">
<img src="assets\man.jpg">
<a href="category.php?cat=men" class="bg-teal-500 text-white px-4 py-2 mt-2 w-full rounded block text-center hover:bg-teal-600">
Shop All
</a>
</div>

<div class="bg-white p-3 rounded shadow">
<img src="assets/kids.jpg">
<a href="category.php?cat=kids" class="bg-teal-500 text-white px-4 py-2 mt-2 w-full rounded block text-center hover:bg-teal-600">
Shop All
</a>
</div>

<div class="bg-white p-3 rounded shadow">
<img src="assets/beauty.jpg">
<a href="category.php?cat=all" class="bg-teal-500 text-white px-4 py-2 mt-2 w-full rounded block text-center hover:bg-teal-600">
Shop All
</a>
</div>

</div>

</div>


<!-- CHATBOX -->
<div class="chatbox">

<button onclick="toggleChat()" class="bg-teal-500 text-white w-14 h-14 rounded-full shadow-lg">
<i class="fa fa-comment"></i>
</button>

<div id="chatWindow" class="chat-window bg-white w-72 h-96 shadow-lg mt-2 rounded flex flex-col">

<div class="bg-teal-500 text-white p-3">
Support Chat
</div>

<div class="flex-1 p-3 overflow-y-auto" id="chatMessages">
</div>

<div class="p-2 border-t flex">
<input id="chatInput" class="flex-1 border rounded px-2">
<button onclick="sendMessage()" class="bg-teal-500 text-white px-3 ml-2 rounded">
Send
</button>
</div>

</div>

</div>


<!-- FOOTER -->
<footer class="bg-black text-white text-center p-4">
© 2026 TrendAura
</footer>



<!-- JS -->
<script>

let slides = document.querySelectorAll(".slide");
let index = 0;

function showSlide(i){
slides.forEach(s=>s.classList.remove("active"));
slides[i].classList.add("active");
}

function nextSlide(){
index = (index+1) % slides.length;
showSlide(index);
}

function prevSlide(){
index = (index-1+slides.length) % slides.length;
showSlide(index);
}

setInterval(nextSlide,3000);


// CHAT
function toggleChat(){
let chat = document.getElementById("chatWindow");
chat.style.display = chat.style.display=="block" ? "none":"block";
}

function sendMessage(){

let input = document.getElementById("chatInput");
let msg = input.value;

if(msg=="") return;

let div = document.createElement("div");
div.className="bg-gray-200 p-2 rounded mb-2";
div.innerText = msg;

document.getElementById("chatMessages").appendChild(div);

input.value="";
}

</script>


</body>
</html>
