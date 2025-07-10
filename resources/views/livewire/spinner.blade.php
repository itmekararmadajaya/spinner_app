<div class="bg-[#031843]">
    <style>
        #parent {
            transform: rotate(-90deg);
            transform-origin: center;
            display: inline-block;
            margin-left: 500px;
        }
    </style>
    <div align="center" id="parent">
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 p-6 h-screen" wire:ignore>
            <!-- Wheel Canvas -->
            <div class="relative w-[600px] h-[600px] flex items-center justify-center bg-no-repeat bg-center bg-contain">
                <div style="position: relative; width: 600px; height: 600px; margin: auto; rotate-[-45deg] origin-center">
                    <!-- Judul Spin to Win -->
                    <div class="absolute top-[-250px] left-1/2 -translate-x-1/2 
                                text-center font-bold text-[#FFD700] z-20
                                whitespace-nowrap bg-[#031843] p-3 rounded-lg" style="width: 600px;">
                        <img src="{{asset('asset/logo/logona2.png')}}" alt="" srcset="">
                    </div>
                    <div class="absolute top-[-130px] left-1/2 -translate-x-1/2 
                                text-center font-bold text-[#FFD700] z-20
                                whitespace-nowrap" style="font-size: 80px;">
                        Spin to Win!
                    </div>

                    <!-- Anak Panah -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="#FFD700"
                        stroke="#B91C1C"
                        stroke-width="2"
                        style="position: absolute;
                                top: -43px;
                                left: 52%;
                                transform: translateX(-50%) rotate(180deg);
                                width: 60px;
                                height: 80px;
                                z-index: 10;">
                        <path d="M12 2L6 12h12L12 2z" />
                    </svg>

                    <!-- Lingkaran hitam -->
                    <div style="
                        position: absolute;
                        top: -5px;
                        left: -45px;
                        background-color: black;
                        border-radius: 50%;
                        z-index: 0;
                        box-shadow: 0 0 20px #FFD700;
                    " class="black-circle"></div>
                    
                    <!-- Canvas Wheel -->
                    <canvas id="canvas"
                            data-responsiveMinWidth="180"
                            data-responsiveScaleHeight="true"
                            style="position: absolute; top: 0; left: -40px; z-index: 1;"></canvas>

                             <!-- Tombol SPIN di tengah lingkaran -->
                            <button onclick="startSpin()" id="spin-btn" style="
                                position: absolute;
                                top: 350px;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                z-index: 2;
                                background-color: black;
                                color: white;
                                border: none;
                                border-radius: 9999px;
                                padding: 25px 20px;
                                font-weight: bold;
                                font-size: 16px;
                                cursor: pointer;
                                box-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
                            ">
                                SPIN
                            </button>
                </div>
            </div>
        </div>
    </div>
    <div id="custom-alert" class="hidden fixed inset-0 flex items-center justify-center z-[9999] -rotate-90">
        <div id="custom-alert-box"
            class="bg-yellow-50 rounded-2xl shadow-2xl border-4 border-red-700 text-center p-6 max-w-sm w-full
                    transform scale-95 opacity-0 transition-all duration-500 ease-out">
            <div class="text-5xl mb-3">🎉</div>
            <h2 class="text-3xl font-extrabold text-red-700 mb-1 drop-shadow">
                Selamat!
            </h2>
            <h2 id="alert-title" class="text-2xl font-extrabold text-red-700 mb-1 drop-shadow"></h2>
            <button type="button"
                    class="mt-4 bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-6 rounded-lg shadow-md
                        transition duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-300 cursor-pointer"
                    onclick="okButton();">
                OK
            </button>
            <button type="button"
                    class="mt-4 bg-red-700 hover:bg-red-800 text-white font-semibold py-2 px-6 rounded-lg shadow-md
                        transition duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-300 cursor-pointer"
                    onclick="batalButton();">
                Batal
            </button>
        </div>
    </div>
<!-- Kalau mau ubah ukuran wheel, ubah dulu width dan height di id=parent & id=parent2. Kemudian ubah juga wheelSize. Setelah itu tinggal sesuaikan anak panah dan lingkaran button spinner -->
    @push('scripts')
        <script>
            let wheelSize = 700;
            var segments = @json($finalDatas);
            let theWheel;
            let audio = new Audio(`{{asset('asset/sound/tick.mp3')}}`);
            let wheelSpinning = false;

            const canvas = document.getElementById("canvas");
            canvas.width = wheelSize;
            canvas.height = wheelSize;

            document.querySelector('.black-circle').style.width = `${wheelSize + 10}px`;
            document.querySelector('.black-circle').style.height = `${wheelSize + 10}px`;

            document.getElementById("spin-btn").style.transform = `translate(-50%, -50%) scale(${wheelSize / 370})`;

            //Winner
            let idWinner = null;

            initiateWheel();

            function initiateWheel(){
                theWheel = new Winwheel({
                            'outerRadius'     : wheelSize / 2 - 5,
                            'innerRadius'     : 50,
                            'responsive'      : true,
                            'textFontSize'    : 18,
                            'textFontFamily'  : "Poppins",
                            'textOrientation' : 'vertical',
                            'textAlignment'   : 'outer',
                            'numSegments'     : segments.length,
                            'segments'        : segments,
                            'animation' : {
                                'type'     : 'spinToStop',
                                'duration' : 10,
                                'spins'    : 3,
                                'callbackFinished' : alertPrize,
                                'callbackSound'    : playSound,
                                'soundTrigger'     : 'pin'
                            },
                            'pins' : {
                                'number'     : segments.length * 5,
                                'fillStyle'  : 'silver',
                                'outerRadius': 4,
                            }
                        });
            }

            function playSound() {
                audio.pause();
                audio.currentTime = 0;
                audio.play();
            }

            function startSpin() {
                if (!wheelSpinning && theWheel) {
                    theWheel.rotationAngle = theWheel.rotationAngle % 360;
                    theWheel.animation.spins = 10;
                    theWheel.startAnimation();
                    wheelSpinning = true;
                }
            }

            function resetWheel() {
                if (theWheel) {
                    theWheel.stopAnimation(false);
                    theWheel.rotationAngle = 0;
                    theWheel.draw();
                    wheelSpinning = false;
                }
            }

            function showCustomAlert(title, message) {
                const alert = document.getElementById("custom-alert");
                const alertBox = document.getElementById("custom-alert-box");
                document.getElementById("alert-title").textContent = title;

                // Show
                alert.classList.add("show");
                setTimeout(() => {
                    alertBox.classList.add("opacity-100", "scale-100");
                }, 10);
            }

            function hideCustomAlert(){
             const alert = document.getElementById("custom-alert");
                const alertBox = document.getElementById("custom-alert-box");

                setTimeout(() => {
                    alertBox.classList.remove("opacity-100", "scale-100");
                alert.classList.remove("show");
                }, 100);
            }

            function okButton(id){
                @this.call('updateWinner', idWinner);
                @this.call('getData').then((result) => {
                    segments = result;

                    // Set canvas ulang kalau perlu
                    // const canvas = document.getElementById('canvas');
                    // canvas.width = 420;
                    // canvas.height = 420;
                    
                    //Reset Wheel
                    theWheel = null;
                    initiateWheel();
                })

                idWinner = null;
                hideCustomAlert();
            }

            function batalButton(){
                idWinner = null;
                hideCustomAlert();
            }

            function alertPrize(indicatedSegment) {
                let duration = 5 * 1000;
                let animationEnd = Date.now() + duration;
                let defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 1000 };

                let interval = setInterval(function() {
                    let timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }

                    confetti(Object.assign({}, defaults, { particleCount: 50, origin: { x: Math.random(), y: Math.random() - 0.2 } }));
                }, 250);
                
                //Pass Winner ID
                idWinner = indicatedSegment.id;
                //Show Alert Winner
                showCustomAlert(indicatedSegment.nama);
                
                theWheel.stopAnimation(false);
                wheelSpinning = false;
            }
        </script>
    @endpush
</div>
