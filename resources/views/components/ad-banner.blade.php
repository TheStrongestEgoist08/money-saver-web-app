
{{-- Ad Banner --}}
<div
    x-data="adPopup()"
    x-init="init()"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-[9999] bg-black/70 backdrop-blur-md flex items-center justify-center p-4"
        style="display: none;"
    >
        {{-- Popup Card --}}
        <div
            @click.away="canClose && closeAd()"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative bg-white rounded-[30px] overflow-hidden shadow-2xl max-w-3xl w-full"
        >
            {{-- Close Button with Circular Countdown --}}
            <button
                @click="canClose && closeAd()"
                :disabled="!canClose"
                class="absolute top-4 right-4 z-20 w-14 h-14 rounded-full bg-white/95 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-xl flex items-center justify-center disabled:cursor-not-allowed group"
            >
                {{-- SVG Circular Progress --}}
                <svg class="w-14 h-14 -rotate-90 absolute" viewBox="0 0 36 36">
                    {{-- Background Circle --}}
                    <path
                        d="M18 2.0845
                           a 15.9155 15.9155 0 0 1 0 31.831
                           a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none"
                        stroke="#e5e7eb"
                        stroke-width="3"
                    />

                    {{-- Animated Progress Circle --}}
                    <path
                        :stroke-dasharray="dashArray"
                        d="M18 2.0845
                           a 15.9155 15.9155 0 0 1 0 31.831
                           a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none"
                        stroke="#ef4444"
                        stroke-width="3"
                        stroke-linecap="round"
                        class="transition-all duration-1000 ease-linear"
                    />
                </svg>

                {{-- Countdown Text --}}
                <span
                    class="text-xl font-bold text-gray-800 group-hover:text-white transition-colors relative z-10"
                    x-text="waitTime"
                ></span>
            </button>

            {{-- Badge --}}
            <div class="absolute top-4 left-4 z-20">
                <span class="px-4 py-2 rounded-full bg-red-500 text-white text-sm font-bold shadow-lg animate-pulse">
                    LIMITED OFFER
                </span>
            </div>

            {{-- Image --}}
            <div class="relative">
                <img
                    src="{{ asset('storage/ads/ad1.jpg') }}"
                    class="w-full h-[350px] object-cover"
                    alt="Advertisement"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                <div class="absolute bottom-8 left-8 text-white">
                    <h1 class="text-5xl font-extrabold mb-2 drop-shadow-lg">
                        BIG SALE
                    </h1>

                    <p class="text-lg opacity-90">
                        Exclusive discounts available now
                    </p>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-6 items-center">

                    <div>
                        <h2 class="text-3xl font-black text-gray-900 mb-3">
                            Special Promo Offer
                        </h2>

                        <p class="text-gray-600 leading-relaxed mb-5">
                            Enjoy huge discounts, rewards, and exclusive access
                            to premium features before the offer expires.
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <a
                                href="#"
                                class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all duration-300 shadow-lg hover:scale-105"
                            >
                                View Offer
                            </a>

                            <button
                                @click="canClose && closeAd()"
                                :disabled="!canClose"
                                class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 font-semibold transition disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Maybe Later
                            </button>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
                        <h3 class="text-2xl font-bold mb-3">
                            Promo Benefits
                        </h3>

                        <ul class="space-y-3">
                            <li class="flex items-center gap-2">
                                ✅ Exclusive Discounts
                            </li>

                            <li class="flex items-center gap-2">
                                ✅ Premium Access
                            </li>

                            <li class="flex items-center gap-2">
                                ✅ Limited-Time Rewards
                            </li>

                            <li class="flex items-center gap-2">
                                ✅ Fast Customer Support
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function adPopup() {
        return {
            open: false,
            canClose: false,

            waitTime: 10,
            forcedWait: 10,

            popupDelay: 45,

            dashArray: "0, 100",

            timer: null,
            countdownInterval: null,

            init() {
                this.startPersistentTimer()
            },

            startPersistentTimer() {
                const now = Math.floor(Date.now() / 1000)

                let nextPopupTime = localStorage.getItem('next_ad_popup')

                if (!nextPopupTime) {
                    nextPopupTime = now + this.popupDelay
                    localStorage.setItem('next_ad_popup', nextPopupTime)
                }

                nextPopupTime = parseInt(nextPopupTime)

                let remaining = nextPopupTime - now

                if (remaining <= 0) {
                    this.showPopup()
                    return
                }

                this.timer = setTimeout(() => {
                    this.showPopup()
                }, remaining * 1000)
            },

            updateCircle() {
                const progress =
                    ((this.forcedWait - this.waitTime) / this.forcedWait) * 100

                this.dashArray = `${progress}, 100`
            },

            showPopup() {

                // Prevent duplicate intervals
                clearInterval(this.countdownInterval)

                this.open = true
                this.canClose = false

                this.waitTime = this.forcedWait

                // Reset circle
                this.dashArray = "0, 100"

                // Prevent background scroll
                document.body.style.overflow = 'hidden'

                // Start countdown
                this.countdownInterval = setInterval(() => {

                    this.waitTime--

                    this.updateCircle()

                    // Stop exactly at 0
                    if (this.waitTime <= 0) {

                        clearInterval(this.countdownInterval)

                        this.waitTime = '✕'

                        this.canClose = true

                        this.dashArray = "100, 100"
                    }

                }, 1000)
            },

            closeAd() {

                if (!this.canClose) return

                this.open = false

                // Enable scroll again
                document.body.style.overflow = ''

                // Clear countdown
                clearInterval(this.countdownInterval)

                // Save next popup time
                const nextTime =
                    Math.floor(Date.now() / 1000) + this.popupDelay

                localStorage.setItem('next_ad_popup', nextTime)

                clearTimeout(this.timer)

                this.startPersistentTimer()
            }
        }
    }
</script>
