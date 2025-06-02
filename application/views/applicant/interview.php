<?php
$creds = getCreds('AHR');
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
    rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/interview-call.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/loader.css" />

<div class="interview-container">
    <div class="header">
        <div class="logo-wrapper">
            <img src="<?php echo AWS_S3_BUCKET_URL . $company['logo']; ?>" alt="<?php echo $company['name']; ?>" />
        </div>
        <div class="title-wrapper">
            <div class="page-title">
                <span>Interviewing for job
                    "<?php echo trim($portal_job_list['job_title'] ? $portal_job_list['job_title'] : $portal_job_list['desired_job_title']); ?>"</span>
            </div>
        </div>

        <div class="right-section">
            <span><?php echo $company['name']; ?></span>
        </div>
    </div>

    <div class="main">
        <div id="conversation" class="conversation">
            <div class="bot">
                <div class="icon-wrapper">
                    <div class="image">
                        <img src="/assets/images/Michael.jpg" alt="Michael Photo" />
                    </div>

                    <div class="layer-1"></div>
                    <div class="layer-2"></div>
                    <div class="layer-3"></div>
                </div>
                <span class="caller-title">Michael (AI Interviewer)</span>
            </div>

            <div class="applicant">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="245" height="245" viewBox="0 0 245 245" fill="none">
                        <g clip-path="url(#clip0_1957_6058)">
                            <path
                                d="M122.585 155.507C91.2411 155.507 65.8316 130.098 65.8316 98.7536C65.8316 67.4095 91.2411 42 122.585 42C153.929 42 179.339 67.4095 179.339 98.7536C179.339 130.098 153.929 155.507 122.585 155.507Z"
                                fill="url(#paint0_radial_1957_6058)" />
                            <path
                                d="M22.3329 236.689C40.5422 255.697 90.0841 242.733 118.484 242.733C146.885 242.733 202.057 255.697 220.267 236.689C204.043 167.25 151.985 160.297 118.484 160.297C84.9836 160.297 41.8023 175.686 22.3329 236.689Z"
                                fill="url(#paint1_radial_1957_6058)" />
                        </g>
                        <defs>
                            <radialGradient id="paint0_radial_1957_6058" cx="0" cy="0" r="1"
                                gradientUnits="userSpaceOnUse"
                                gradientTransform="translate(135.723 78.7614) rotate(180) scale(83.1383 83.1383)">
                                <stop stop-color="white" />
                                <stop offset="1" stop-color="#D1D1D1" />
                            </radialGradient>
                            <radialGradient id="paint1_radial_1957_6058" cx="0" cy="0" r="1"
                                gradientUnits="userSpaceOnUse"
                                gradientTransform="translate(135.21 186.996) rotate(180) scale(84.09 84.09)">
                                <stop stop-color="white" />
                                <stop offset="1" stop-color="#D1D1D1" />
                            </radialGradient>
                            <clipPath id="clip0_1957_6058">
                                <rect width="244" height="244" fill="white"
                                    transform="matrix(-1 0 0 1 244.867 0.288208)" />
                            </clipPath>
                        </defs>
                    </svg>

                    <div class="layer-1"></div>
                    <div class="layer-2"></div>
                    <div class="layer-3"></div>
                </div>
                <span
                    class="caller-title"><?php echo $portal_job_list['first_name'] . ' ' . $portal_job_list['last_name']; ?></span>
            </div>
        </div>

        <div class="button-wrapper">
            <div class="timer-wrapper">
                <span class="timer">Total Call Time: &nbsp; <span id="time">00:00</span> </span>
            </div>

            <button class="end-call">
                <svg width="38" height="15" viewBox="0 0 38 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.30351 13.643L1.32627 10.6657C1.04876 10.3905 0.831798 10.0603 0.689284 9.69631C0.546767 9.33234 0.481838 8.94262 0.498655 8.55211C0.51547 8.16159 0.613658 7.77889 0.786935 7.42853C0.960213 7.07816 1.20476 6.76784 1.5049 6.51745C4.89055 3.79545 8.86748 1.90556 13.1161 0.999626C17.0377 0.125472 21.1036 0.125474 25.0251 0.999627C29.2913 1.91143 33.2827 3.81535 36.676 6.55714C36.9753 6.80678 37.2193 7.11599 37.3924 7.46508C37.5656 7.81417 37.6642 8.1955 37.682 8.58479C37.6997 8.97408 37.6362 9.36279 37.4955 9.72619C37.3547 10.0896 37.1399 10.4197 36.8646 10.6955L33.8874 13.6727C33.4105 14.1591 32.773 14.4555 32.0938 14.5066C31.4145 14.5577 30.7399 14.3601 30.1956 13.9506C29.1171 13.1235 27.9486 12.421 26.7122 11.8566C26.2235 11.6349 25.8085 11.2778 25.5166 10.8275C25.2246 10.3772 25.0678 9.8527 25.0648 9.31605V6.79532C21.1675 5.72353 17.0531 5.72353 13.1558 6.79532L13.1558 9.31605C13.1528 9.8527 12.9961 10.3772 12.7041 10.8275C12.4121 11.2778 11.9972 11.6349 11.5084 11.8566C10.2721 12.421 9.1035 13.1235 8.02506 13.9506C7.47497 14.3647 6.79188 14.5621 6.10571 14.5054C5.41953 14.4487 4.77814 14.1418 4.30351 13.643Z"
                        fill="white" />
                </svg>
            </button>
        </div>

        <div class="footer">
            <p>Powered by</p> <img src="/assets/images/automotoHr-logo.png" alt="automoto Logo" />
        </div>
    </div>

    <!-- Microphone Connection Popup -->
    <div id="microphonePopup" class="microphone-popup hidden">
        <div class="microphone-popup-content">
            <h3>🎤 Microphone Access Required</h3>
            <p>We need access to your microphone to conduct the interview. Please click the button below to enable
                microphone access.</p>
            <button id="enableMicrophoneBtn">Click Here to Enable Microphone</button>
        </div>
    </div>

    <!-- Call Ended Popup -->
    <div id="callendedPopup" class="modal-popup hidden">
        <div class="modal-popup-content">
            <h3>Interview Ended</h3>
            <p>Thank you for participating in the interview.</p>
            <p>We wish you the best of luck!</p>
            <button id="enableMicrophoneBtn" onclick="window.location.href='/';">Return to Home</button>
        </div>
    </div>

    <!-- Call Ended Popup -->
    <div id="connectingPopup" class="modal-popup">
        <div class="modal-popup-content">
            <h3 style="font-size: 18px;font-weight:500;">Please hold on a moment while we prepare your interview</h3>
            <span class="loader"></span>
        </div>
    </div>
</div>
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
    // let chatId = '';

    const job_list_sid = `<?php echo $portal_job_list["jobs_list_sid"]; ?>`;
    const ServerPath = '<?php echo getAPIServerUrlForBrowser(true); ?>';
    let socket;
    let currentAudio = null;
    let interviewStarted = false;
    let mediaRecorder = null;
    let audioStream = null;
    let audioQueue = [];
    let isPlaying = false;
    let audioContext;
    const sampleRate = 24000;
    let gainNode = null;

    // Speeking detection
    let isSpeaking = false;
    let silenceTimer = null;
    const SILENCE_DELAY = 2000;

    // Call timer variables
    let timerInterval;
    let waitingTimeSeconds = 0;
    const waitingTimelimit = 5 * 60; // 15 minutes in seconds

    let frequencyAnalyser = null;
    let frequencyDataArray = null;
    let frequencyCheckInterval = null;

    document.addEventListener('DOMContentLoaded', function () {

        function startCallTimer() {
            const timeElement = document.querySelector('.timer #time');
            let [minutes, seconds] = timeElement.textContent.split(':').map(Number);

            timerInterval = setInterval(() => {
                seconds++;
                if (seconds === 60) {
                    seconds = 0;
                    minutes++;
                }

                // Format MM:SS
                const formattedTime =
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');

                timeElement.textContent = formattedTime;
            }, 1000);
        }

        function stopCallTimer() {
            clearInterval(timerInterval);
        }

        const counterInterval = setInterval(() => {
            if (isSpeaking === true || isPlaying === true) {
                waitingTimeSeconds = 0;
            } else {
                waitingTimeSeconds++;
            }

            // Convert seconds to mm:ss format
            const minutes = Math.floor(waitingTimeSeconds / 60);
            const remainingSeconds = waitingTimeSeconds % 60;
            // console.log(
            //     `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`
            // );

            if (waitingTimeSeconds >= waitingTimelimit) {
                clearInterval(counterInterval);
                console.log('stop in counter interval ...');
                stopInterview();
                console.log("Waited upto 5 minutes.");
            }
        }, 999);

        // Initialize AudioContext
        function initAudioContext() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)({
                    sampleRate: sampleRate
                });

                // Create main gain node
                gainNode = audioContext.createGain();
                gainNode.gain.value = 1.0;
                gainNode.connect(audioContext.destination);
            }
            return audioContext;
        }

        function startInterview() {
            interviewStarted = true;
            startCallTimer();
            try {
                socket.emit('startSpeechRecognition', { job_list_sid });
            } catch (e) {
                console.error('Error starting interview:', e);
            }
        }

        // MediaRecorder Setup and Frequency Tracking
        function setupMediaRecorder(stream) {
            try {
                // Check supported MIME types
                const mimeTypes = [
                    'audio/webm',
                    'audio/webm;codecs=opus',
                    'audio/ogg;codecs=opus',
                    'audio/wav',
                    ''
                ];

                let selectedMimeType = '';
                for (let type of mimeTypes) {
                    if (MediaRecorder.isTypeSupported(type)) {
                        selectedMimeType = type;
                        console.log('Using MIME type:', selectedMimeType);
                        break;
                    }
                }

                // Create the MediaRecorder
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: selectedMimeType,
                    audioBitsPerSecond: 16000
                });

                // Configure handlers
                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0 && socket && socket.connected) {
                        socket.emit('audioData', {
                            clientId: socket.id,
                            job_list_sid,
                            data: event.data
                        });
                    }
                };

                mediaRecorder.onerror = (event) => {
                    console.error('MediaRecorder error:', event.error);
                };

                // Start recording
                mediaRecorder.start(100);
                console.log('MediaRecorder started!', mediaRecorder.state);

                window.mediaRecorder = mediaRecorder;
            } catch (err) {
                console.error('Failed to create MediaRecorder:', err);
            }
        }

        function clearAudio() {
            if (currentAudio) {
                if(!currentAudio.paused)
                {
                    currentAudio.pause();
                }
                currentAudio = null;
                isPlaying = false;
                audioQueue = [];
                console.log('🔇 Audio cleared - user speaking');
            }
        }

        function setupSocketConnection() {
            // Connect to your Node.js server with Socket.IO
            socket = io(ServerPath);
            socket.binaryType = 'arraybuffer';

            // Handle connection events
            socket.on('connect', () => {
                console.log('Connected to Socket.IO server');
                startInterview();
            });

            // Handle status updates
            socket.on('status', (data) => {
                if (data.status === 'ready') {
                    // Deepgram connection is ready, setup audio recording
                    console.log(`Status: ${data.status} - ${data.message}`);

                    // Setup MediaRecorder for sending audio data
                    if (audioStream) {
                        setupMediaRecorder(audioStream);
                    } else {
                        console.error('Audio steaming not define!');
                    }
                }
                else {
                    console.log(`Status: ${data.status} - ${data.message}`);
                }
            });

            /* -----------------------
             Play in audio wav format
            ----------------------- */

            socket.on('message', async (data) => {
                try {

                    if (isSpeaking) {
                        return;
                    }
                    const parsedData = JSON.parse(data);

                    if (parsedData.type === 'audio') {
                        initAudioContext();

                        try {

                            const binaryString = atob(parsedData.data);
                            const len = binaryString.length;
                            const bytes = new Uint8Array(len);

                            for (let i = 0; i < len; i++) {
                                bytes[i] = binaryString.charCodeAt(i);
                            }

                            // In Audio play format
                            const blob = new Blob([bytes], { type: 'audio/wav' });
                            const audioUrl = URL.createObjectURL(blob);

                            // Add to queue
                            audioQueue.push(audioUrl);

                            // Start playing if not already playing
                            if (!isPlaying) {
                                hidePopup('connectingPopup');
                                playNextInQueue();
                            }

                        } catch (decodeError) {
                            console.error('Error decoding audio:', decodeError);
                        }
                    }
                } catch (error) {
                    console.error('Error processing message:', error);
                }
            });

            socket.on('close', (is_final) => {
                stopInterview();
            })

            // Handle errors
            socket.on('error', (error) => {
                console.error('Socket.IO error:', error);
            });

            // Handle disconnection
            socket.on('disconnect', () => {
                console.log('Disconnected from Socket.IO server');

                // Stop MediaRecorder if running
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }

                // Attempt to reconnect if interview is still ongoing
                if (interviewStarted) {
                    console.log('Attempting to reconnect...');
                    setTimeout(setupSocketConnection, 2000);
                }
            });

            socket.on('clearAudio', (data) => {
                console.log('clearAudio', data);
                isSpeaking = data.speaking;
                clearAudio();
            })

            return socket;
        }

        /* -----------------------
           Method to listen audio
        ------------------------- */

        function playNextInQueue() {
            if (audioQueue.length === 0) {
                isPlaying = false;
                return;
            }

            isPlaying = true;
            const audioUrl = audioQueue.shift();

            // Create and play audio
            const audio = new Audio();
            currentAudio = audio;

            currentAudio.onended = () => {
                URL.revokeObjectURL(audioUrl); // Clean up
                stopFrequencyAnalysis();
                playNextInQueue();
            };

            currentAudio.onerror = (e) => {
                console.error('Audio playback error:', e);
                URL.revokeObjectURL(audioUrl);
                stopFrequencyAnalysis();
                playNextInQueue(); // Skip to next
            };

            currentAudio.onplay = () => {
                analyzeAudioFrequency(currentAudio); // Start frequency analysis
            };

            currentAudio.src = audioUrl;
            currentAudio.play().catch(e => console.error('Failed to play audio:', e));
        }

        function analyzeAudioFrequency(audioElement) {
            if (!audioContext) return;

            // Create analyser if not exists
            if (!frequencyAnalyser) {
                frequencyAnalyser = audioContext.createAnalyser();
                frequencyAnalyser.fftSize = 2048;
                frequencyDataArray = new Uint8Array(frequencyAnalyser.frequencyBinCount);
            }

            // Connect audio to analyser
            const source = audioContext.createMediaElementSource(audioElement);
            source.connect(frequencyAnalyser);
            frequencyAnalyser.connect(audioContext.destination);

            const layers = document.querySelectorAll('.bot .layer-1, .bot .layer-2, .bot .layer-3');

            // Start frequency monitoring
            frequencyCheckInterval = setInterval(() => {
                frequencyAnalyser.getByteFrequencyData(frequencyDataArray);

                // Calculate average amplitude across frequency spectrum
                let sum = 0;
                let count = 0;

                // Focus on speech frequency range (300Hz - 3400Hz)
                const minFreqIndex = Math.floor((300 * frequencyAnalyser.fftSize) / audioContext.sampleRate);
                const maxFreqIndex = Math.floor((3400 * frequencyAnalyser.fftSize) / audioContext.sampleRate);

                for (let i = minFreqIndex; i < maxFreqIndex && i < frequencyDataArray.length; i++) {
                    sum += frequencyDataArray[i];
                    count++;
                }
                const avgAmplitude = count > 0 ? sum / count : 0;

                // Normalize amplitude (0-255) to scale factor
                const normalizedAmplitude = avgAmplitude / 255;
                const baseScale = 0.5;
                const maxScale = 1.2;
                const scaleRange = maxScale - baseScale;
                const intensity = Math.min(normalizedAmplitude * 3, 1);

                // Animate layers with different intensities for depth effect
                layers.forEach((layer, index) => {

                    const layerDelay = index * 0.05; // Stagger animation
                    const layerIntensity = 0.9 + (index * 0.05); // Vary intensity per layer
                    const targetScale = baseScale + (intensity * (maxScale - baseScale) * layerIntensity);

                    // Smooth interpolation instead of direct assignment
                    const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || '1');
                    const smoothScale = currentScale + (targetScale - currentScale) * 0.3; // Lerp factor
                    const rotation = (normalizedAmplitude * 2 - 1) * 1; // Subtle rotation

                    setTimeout(() => {
                        layer.style.transform = `translateY(-50%) scale(${smoothScale.toFixed(3)}) rotate(${rotation.toFixed(1)}deg)`;
                        layer.style.transition = 'transform 0.1s ease-out';
                    }, layerDelay * 1000);
                });

            }, 100);
        }

        function stopFrequencyAnalysis() {
            if (frequencyCheckInterval) {
                clearInterval(frequencyCheckInterval);
                frequencyCheckInterval = null;
            }

            const layers = document.querySelectorAll('.bot .layer-1, .bot .layer-2, .bot .layer-3');
            layers.forEach((layer, index) => {
                setTimeout(() => {
                    layer.style.transform = 'translateY(-50%) scale(0.5)';
                    layer.style.transition = 'transform 0.15s ease-out';
                }, index * 100);
            });
        }

        function hideMicrophonePopup() {
            const popup = document.getElementById('microphonePopup');
            if (!popup.classList.contains('hidden')) {
                popup.classList.add('hidden');
            }
        }

        function showMicrophonePopup() {
            const popup = document.getElementById('microphonePopup');
            if (popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
            }

            // Add click event to the button
            document.getElementById('enableMicrophoneBtn').addEventListener('click', setupAudioRecording);
        }

        /* ----------------------------------------------------
          Microphone Speaking Detection and Frequency Tracking
        ---------------------------------------------------- */

        function detectSpeaking(stream) {
            const source = audioContext.createMediaStreamSource(stream);
            const analyser = audioContext.createAnalyser();
            analyser.fftSize = 1024;

            const dataArray = new Uint8Array(analyser.fftSize);
            const frequencyDataArray = new Uint8Array(analyser.frequencyBinCount);
            source.connect(analyser);

            // Cache applicant layer elements for frequency animation
            const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
            let lastActiveTime = 0;

            function analyze() {
                analyser.getByteTimeDomainData(dataArray);

                // Compute average volume from waveform
                let sum = 0;
                for (let i = 0; i < dataArray.length; i++) {
                    let sample = dataArray[i] - 128; // Normalize to -128 to +127
                    sum += Math.abs(sample);
                }
                const avg = sum / dataArray.length;

                // Simple threshold: tune this value
                const threshold = 8;

                // Frequency analysis (frequency domain)
                analyser.getByteFrequencyData(frequencyDataArray);

                // Calculate weighted average for voice frequencies
                let weightedSum = 0;
                let totalWeight = 0;

                const fundamentalStart = Math.floor((85 * analyser.fftSize) / audioContext.sampleRate);
                const fundamentalEnd = Math.floor((300 * analyser.fftSize) / audioContext.sampleRate);
                const harmonicsStart = Math.floor((300 * analyser.fftSize) / audioContext.sampleRate);
                const harmonicsEnd = Math.floor((8000 * analyser.fftSize) / audioContext.sampleRate);

                // Weight fundamental frequencies more heavily
                for (let i = fundamentalStart; i < fundamentalEnd; i++) {
                    weightedSum += frequencyDataArray[i] * 2;
                    totalWeight += 2;
                }

                for (let i = harmonicsStart; i < harmonicsEnd; i++) {
                    weightedSum += frequencyDataArray[i];
                    totalWeight += 1;
                }

                const avgAmplitude = totalWeight > 0 ? weightedSum / totalWeight : 0;
                const normalizedAmplitude = avgAmplitude / 255;

                if (avg > threshold) {
                    onVoiceDetected(avg);
                } else {
                    onSilenceDetected(avg);
                }

                // console.log('normalizedAmplitude', normalizedAmplitude);

                // Frequency-based layer animation with smooth transitions
                const frequencyThreshold = 0.10;
                const isCurrentlyActive = normalizedAmplitude > frequencyThreshold;
                const baseScale = 0.5;

                if (isCurrentlyActive) {
                    lastActiveTime = Date.now();

                    const maxScale = 1.2; // Reduced for subtlety
                    const intensity = Math.min(normalizedAmplitude * 3, 1); // Amplify but cap at 1

                    applicantLayers.forEach((layer, index) => {
                        const layerDelay = index * 0.05; // Stagger animation
                        const layerIntensity = 0.9 + (index * 0.05); // Vary intensity per layer
                        const targetScale = baseScale + (intensity * (maxScale - baseScale) * layerIntensity);

                        // Smooth interpolation instead of direct assignment
                        const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || '1');
                        const smoothScale = currentScale + (targetScale - currentScale) * 0.3; // Lerp factor

                        setTimeout(() => {
                            layer.style.transform = `translateY(-50%) scale(${smoothScale.toFixed(3)})`;
                            layer.style.transition = 'transform 0.1s ease-out';
                        }, layerDelay * 1000);
                    });

                } else {
                    // Gradual fade out
                    const timeSinceActive = Date.now() - lastActiveTime;
                    if (timeSinceActive > 100) {
                        applicantLayers.forEach((layer, index) => {
                            const currentScale = parseFloat(layer.style.transform.match(/scale\(([^)]+)\)/)?.[1] || baseScale);
                            const fadeScale = currentScale + (baseScale - currentScale) * 0.15; // Gradual return to 1
                            layer.style.transform = `translateY(-50%) scale(${fadeScale.toFixed(3)})`;
                            layer.style.transition = 'transform 0.15s ease-out';
                        });
                    }
                }

                requestAnimationFrame(analyze);
            }

            analyze();
        }

        // Updated voice detection handler
        function onVoiceDetected(volume) {
            if (!isSpeaking) {
                isSpeaking = true;
                console.log(`🎤 Voice detected (volume: ${volume.toFixed(1)}) - User is speaking`);

                // Pause audio if playing
                if (currentAudio && !currentAudio.paused) {
                    currentAudio.pause();
                    console.log('🔇 Audio paused - user speaking');
                    audioQueue = [];

                    // Notify server that user is speaking
                    if (socket && socket.connected) {
                        socket.emit('userSpeaking', { speaking: true, job_list_sid });
                    }

                    setTimeout(() => {
                        if(isSpeaking) {
                            clearAudio();
                        } else {
                            currentAudio.play().catch(e => {
                                console.error('Error resuming audio:', e);
                            });
                            console.log('🔊 Audio resumed');
                        }
                    }, SILENCE_DELAY);
                }
            }

            // Clear silence timer
            if (silenceTimer) {
                clearTimeout(silenceTimer);
                silenceTimer = null;
            }
        }

        // Updated silence detection handler
        function onSilenceDetected(volume) {
            // Only process if user was speaking
            if (isSpeaking && !silenceTimer) {
                silenceTimer = setTimeout(() => {
                    isSpeaking = false;
                    const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
                    // Reset layers when not speaking
                    applicantLayers.forEach(layer => {
                        layer.style.transform = 'translateY(-50%) scale(0.5)';
                        layer.style.transition = 'transform 0.08s cubic-bezier(0.4, 0, 0.2, 1)';
                    });

                    console.log(`🔇 Silence detected (volume: ${volume.toFixed(1)}) - User stopped speaking`);

                    // Notify server that user stopped speaking
                    if (socket && socket.connected) {
                        socket.emit('userSpeaking', { speaking: false, job_list_sid });
                    }
                    silenceTimer = null;
                }, SILENCE_DELAY);
            }
        }

        // Setup audio recording function. After microphone connection start Websocket
        function setupAudioRecording() {
            // Request access to the microphone
            navigator.mediaDevices.getUserMedia({
                audio: true
            })
                .then(async (stream) => {
                    hideMicrophonePopup();

                    await setupSocketConnection();

                    console.log('Got microphone access!');
                    audioStream = stream
                    // Store the stream for later use
                    window.audioStream = audioStream;

                    initAudioContext();

                    // Microphone Speaking Detection and Frequency Tracking
                    detectSpeaking(stream);

                })
                .catch(error => {
                    console.error('Error accessing microphone:', error);

                    // Update popup content to show error
                    const popupContent = document.querySelector('.microphone-popup-content');
                    popupContent.innerHTML = `
                    <h3>❌ Microphone Access Denied</h3>
                    <p>Please allow microphone access in your browser settings and refresh the page to start the interview.</p>
                    <button onclick="location.reload()">Refresh Page</button>
                `;
                });
        }

        function stopInterview() {
            console.log('Interview stoped ...');
            const callendPopup = document.querySelector('#callendedPopup');
            if (callendPopup) {
                callendPopup.classList.remove('hidden');
            }

            interviewStarted = false;

            // Stop audio recording
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }

            // Stop all audio tracks
            if (audioStream) {
                audioStream.getTracks().forEach(track => track.stop());
            }

            // Reset applicant layers
            const applicantLayers = document.querySelectorAll('.applicant .layer-1, .applicant .layer-2, .applicant .layer-3');
            applicantLayers.forEach(layer => {
                layer.style.transform = 'translateY(-50%) scale(0.5)';
                layer.style.transition = 'transform 0.3s ease-out';
            });

            // Stop frequency analysis
            stopFrequencyAnalysis(); // For AI audio

            if (currentAudio) {
                if (!currentAudio.paused) {
                    currentAudio.pause();
                }
                currentAudio = null;
                isPlaying = false;
            }

            stopCallTimer();
            socket.emit('generate_reports', true);
            socket.disconnect();
        }

        // Check Microphone Permission Status
        async function checkMicrophonePermission() {
            try {
                // Check if getUserMedia is supported
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    console.log('getUserMedia not supported');
                    return false;
                }

                // Try to get microphone access without showing permission prompt
                const permissionStatus = await navigator.permissions.query({ name: 'microphone' });

                if (permissionStatus.state === 'granted') {
                    console.log('Microphone permission already granted');
                    hideMicrophonePopup();
                    setupAudioRecording();
                    return true;
                } else {
                    console.log('Microphone permission not granted:', permissionStatus.state);
                    showMicrophonePopup();
                    return false;
                }
            } catch (error) {
                console.log('Error checking microphone permission:', error);
                return false;
            }
        }

        const endCallButton = document.querySelector('button.end-call');
        endCallButton.addEventListener('click', (e) => {
            console.log('enc call buton click stoping ...');
            stopInterview();
        })

        function hidePopup(id) {
            const popup = document.getElementById(id);
            if (!popup.classList.contains('hidden')) {
                popup.classList.add('hidden');
            }
        }

        checkMicrophonePermission();
    });
</script>