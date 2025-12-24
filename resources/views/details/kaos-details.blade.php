<x-app-layout>


	<div class="mt-10 ml-3" id="backButton">
		<a href="/" class="text-black  hover:cursor-pointer"><x-heroicon-s-arrow-left class="w-5" /></a>
	</div>
	<div class="max-w-screen-xl mx-auto mt-10 p-4 text-white flex flex-col min-h-screen" id="productCart">
		<!-- Confirmation Modal -->
		<div id="confirmationModal"
			class="modal hidden fixed inset-0  items-center justify-center bg-black bg-opacity-50 z-50">
			<div class="modal-content bg-white p-4 rounded shadow-lg max-w-md h-auto mx-3">
				<p class="text-lg text-black font-semibold">Apakah kamu yakin ingin membeli Produk ini?</p>
				<div class="flex justify-end mt-4">
					<button id="confirmBuy" class="px-4 py-2 bg-green-500 text-white rounded mr-2 w-auto">YA</button>
					<button id="cancelBuy" class="px-4 py-2 bg-red-500 text-white rounded">BATAL</button>
				</div>
			</div>
		</div>

		<!-- Grid Layout for Main Content -->
		<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 flex-grow">
			<!-- Left Section: Product Image, Title, and Carousel -->
			<div class="space-y-4">
				<!-- Product Title Container -->
				<div class=" rounded-md sm:block hidden" id="mainTitle">
					<h1 id="productTitle" class="text-3xl font-bold text-black text-center  border-black">
						{{ $details[0]->title }}
					</h1>
				</div>
				<!-- Main Product Image -->
				<div id="mainImage" class="w-full h-80 flex justify-center items-center rounded-md overflow-hidden">
					<img id="currentImage" src="{{$details[0]->image_path}}" onmousemove="zoomImage(event)"
						onmouseleave="zoomOutImage()" alt="Product Image"
						class="zoomable-image object-cover h-full w-full transition-transform duration-300 ease-out bg-repeat bg-contain" />
				</div>
				<!-- small Thumbnails-->
				<div id="thumbnailContainer" class="flex w-full flex-wrap gap-1 justify-between">
					@foreach ($details as $detail)
						<img src="{{ $detail->image_path }}"
							onclick="changeImage('{{ $detail->image_path }}', '{{ $detail->id }}')"
							alt="Thumbnail {{$detail->id}}"
							class="w-24 h-24 cursor-pointer hover:border-red-600 thumbnail hover:border-b-4 rounded-md shadow-md"
							data-index="{{$detail->id}}">
					@endforeach
				</div>
			</div>

			<!-- Middle Section: Product Details -->
			<div class="space-y-4">
				<!-- Pricing and Rating Section -->
				<div class="text-black p-4 space-y-2 rounded-md">
					<p class="text-lg">
					<h1 id="productTitleInPricing" class="text-2xl font-bold text-black sm:hidden block ">
						{{ $details[0]->title }}
					</h1>
					<span id="discountedPrice" class="font-bold text-2xl">
						{{Illuminate\Support\Number::currency(discount($details[0]->price, $details[0]->discount), 'IDR', 'de')}}
					</span>
					<span id="soldTotal" class="font-light text-m">{{$details[0]->sold_total}}</span>
					</span>
					<br />
					<span id="originalPrice" class="line-through" data-price="{{$details[0]->price}}">
						{{Illuminate\Support\Number::currency($details[0]->price, 'IDR', 'de')}}</span>
					</p>
					<div id="productRating" class="flex items-center space-x-2">
						@for ($i = 1; $i <= 5; $i++)
							@if ($i <= $details[0]->rating)
								<x-bi-star-fill class="text-yellow-500" />
							@else
								<x-bi-star class="text-gray-400" />
							@endif
						@endfor
					</div>
					<p id="discountText" class="fw-bold" data-value="{{ $details[0]->discount }}">Diskon:
						{{$details[0]->discount}}%
					</p>
					<p id="productLocation" class="text-lg">
						<span class="fw-bold">Lokasi:</span>
						{{$details[0]->location}}
					</p>
				</div>

				<!-- Product Description -->
				<div class="text-black p-4 rounded-md shadow-md">
					<p id="productDescription">
						<span class="fw-bold">Deskripsi Produk:</span>
						<br />
						{{$details[0]->description}}

					</p>

				</div>
			</div>

			<!-- Right Section: Atur Jumlah dan Catatan -->
			<div class="bg-gray-100 text-black p-4 space-y-4 rounded-md shadow-md">
				<h3 class="text-lg font-semibold">Atur Jumlah dan Catatan</h3>
				<div>
					<label for="variantSelect">Varian Produk:</label>
					<select id="variantSelect" onchange="changeVariants()"
						class="w-full p-2 bg-white border border-gray-300 rounded">
						@foreach ($variants as $variant)
							<option class="w-full p-2 bg-white rounded-md" data-price="{{ $variant->price }}">
								{{$variant->variant}}
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex items-center my-3 flex-wrap space-x-2">
					<label>Quantity:</label>
					<div class="flex space-x-2 my-sm-0 my-3">
						<button class="px-3 py-2 bg-slate-700 text-white rounded" onclick="decreaseQtt()"
							id="decreaseQtt">-</button>
						<input id="quantityInput" type="text" value="1"
							class="text-center w-10 bg-white border border-gray-300 rounded" readonly />
						<button class="px-3 py-2 bg-slate-700 text-white rounded" id="increaseQtt"
							onclick="increaseQtt()">+</button>
					</div>
					</span>
				</div>

				<!-- Pricing Section (with Dynamic Subtotal) -->
				<span id="stockCount" class="my-10"><span class="font-bold">Stok:</span> {{$details[0]->stock}}
					<div class="space-y-1">
						<p class="text-sm text-gray-500">Subtotal</p>
						<p class="text-lg font-semibold text-gray-900">
							<span id="subtotalPrice" class="text-xl">
								{{Illuminate\Support\Number::currency(discount($details[0]->price, $details[0]->discount), 'IDR', 'de')}}
							</span>
						</p>
					</div>


					<!-- Buy Button -->
					<div class="space-y-2">
						<button onclick="goWhatsapp()" class="w-full bg-slate-700 text-white py-2 rounded"
							id="BuyButton">Pesan</button>
					</div>
			</div>
		</div>
	</div>
	@endsection
</x-app-layout>

<script>
	let price = null
	let oriPrice = null
	let originalPrice = null
	let discountEl = null
	let discount1 = null
	let title = null
	let variant = null
	let quantity = 1;   // 👉 ini state untuk quantity

	function goWhatsapp() {
		window.open(`https://wa.me/+628816977857?text=Halo%20saya%20mau%20pesan%20${title.textContent}%20${variant}%20sebanyak%20${quantity}`)
	}

	document.addEventListener("DOMContentLoaded", () => {
		originalPrice = document.getElementById("originalPrice");
		discountEl = document.getElementById("discountText");
		title = document.getElementById("productTitle")
		// Cara 1: pakai getAttribute
		discount1 = discountEl.getAttribute("data-value");
		oriPrice = originalPrice.getAttribute("data-price"); // price attribute

		price = discount(oriPrice, discount1)
	});

	const subtotalEl = document.getElementById("subtotal");
	function formatCurrency(value) {
		const formatted = new Intl.NumberFormat('de-DE', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		}).format(value);

		return `${formatted} IDR`;
	}

	function render() {
		const subTotalPrice = document.getElementById("subtotalPrice");
		const input = document.getElementById("quantityInput");
		input.value = quantity;
		subTotalPrice.textContent = formatCurrency(quantity * price);
	}

	function increaseQtt() {
		quantity++;
		render(price);
	};

	function decreaseQtt() {
		if (quantity > 1) {
			quantity--;
			render(price);
		}
	};

	function zoomImage(e) {
		const currentImage = document.getElementById("currentImage");
		console.log(currentImage)
		const rect = currentImage.getBoundingClientRect();
		const x = ((e.clientX - rect.left) / rect.width) * 100;
		const y = ((e.clientY - rect.top) / rect.height) * 100;

		currentImage.style.transformOrigin = `${x}% ${y}%`;
		currentImage.style.transform = "scale(1.5)";
	};

	function zoomOutImage() {
		const currentImage = document.getElementById("currentImage");

		currentImage.style.transformOrigin = "center";
		currentImage.style.transform = "scale(1)";
	};


	function changeImage(imageSrc, imageNumber) {
		document.getElementById("currentImage").src = imageSrc;

	}

	function discount(price, discount) {
		const discountAmount = price * (discount / 100);
		return price - discountAmount;
	}

	function changeVariants() {

		const select = document.getElementById("variantSelect");
		const selectedOption = select.options[select.selectedIndex];
		let variantPrice = selectedOption.getAttribute("data-price"); // price attribute
		const originalPrice = document.getElementById("originalPrice");
		const discountedPrice = document.getElementById("discountedPrice");
		const subTotalPrice = document.getElementById("subtotalPrice");
		variant = selectedOption.value
		const value = select.value;
		const discountPrice = discount(variantPrice, discount1)
		price = discountPrice
		quantity = 1
		render()

		originalPrice.textContent = formatCurrency(variantPrice)
		discountedPrice.textContent = formatCurrency(discountPrice)
		subTotalPrice.textContent = formatCurrency(discountPrice)
		console.log("Selected variant:", value);
	}
</script>