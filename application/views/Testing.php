<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Camera Capture with Audio</title>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            video,
            canvas,
            img {
                max-width: 100%;
                height: auto;
            }
        </style>
    </head>

    <body>
        <button id="startCamera">Open Camera</button>
        <button id="capturePhoto" style="display: none;">Take Picture</button>
        <button id="startRecording" style="display: none;">Start Recording</button>
        <button id="stopRecording" style="display: none;">Stop Recording</button>
        <button id="toggleCamera" style="display: none;">Toggle Camera</button>

        <video id="videoElement" style="display: none;" autoplay muted></video>
        <canvas id="canvas" style="display: none;"></canvas>
        <img id="capturedImage" style="display: none;" />
        <video id="capturedVideo" controls style="display: none;"></video>

        <div id="permissionStatus" style="margin-top: 20px;"></div>

        <script>
            let videoElement = document.getElementById('videoElement');
            let canvas = document.getElementById('canvas');
            let captureButton = $('#capturePhoto');
            let startRecordingButton = $('#startRecording');
            let stopRecordingButton = $('#stopRecording');
            let toggleCameraButton = $('#toggleCamera');
            let mediaRecorder;
            let recordedChunks = [];
            let currentStream;
            let currentDeviceId = null;
            let backCameraId = null;

            // Function to start the camera and ask for permission
            function startCamera(deviceId) {
                const constraints = {
                    video: {
                        deviceId: deviceId ? { exact: deviceId } : undefined,
                        width: { ideal: 1280 }, // Lower resolution for better performance
                        height: { ideal: 720 }
                    },
                    audio: true // Enable audio capture
                };

                // Check if getUserMedia is supported by the browser
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(function (stream) {
                            // Permission granted
                            document.getElementById('permissionStatus').innerText = "Camera access granted.";
                            videoElement.style.display = 'block';
                            videoElement.srcObject = stream;
                            captureButton.show();
                            startRecordingButton.show();
                            stopRecordingButton.hide();
                            toggleCameraButton.show();

                            // Save the current stream to stop it later if needed
                            if (currentStream) {
                                currentStream.getTracks().forEach(track => track.stop());
                            }
                            currentStream = stream;
                        })
                        .catch(function (err) {
                            // Permission denied or error
                            document.getElementById('permissionStatus').innerText = "Camera access denied or error: " + err.message;
                        });
                } else {
                    // If getUserMedia is not supported
                    document.getElementById('permissionStatus').innerText = "getUserMedia is not supported in this browser.";
                }
            }

            // Function to toggle between cameras
            function toggleCamera() {
                // Get all media devices and find the front and back cameras
                navigator.mediaDevices.enumerateDevices()
                    .then(devices => {
                        let videoDevices = devices.filter(device => device.kind === 'videoinput');
                        let nextDeviceId = null;

                        // Find the next camera device (if it exists)
                        for (let i = 0; i < videoDevices.length; i++) {
                            if (videoDevices[i].deviceId !== currentDeviceId) {
                                nextDeviceId = videoDevices[i].deviceId;
                                break;
                            }
                        }

                        // If there's no next device, toggle back to the first one
                        if (!nextDeviceId) {
                            nextDeviceId = videoDevices[0].deviceId;
                        }

                        // Set the new device ID and restart the camera with the new device
                        currentDeviceId = nextDeviceId;
                        startCamera(currentDeviceId);
                    })
                    .catch(err => {
                        document.getElementById('permissionStatus').innerText = "Error accessing devices: " + err.message;
                    });
            }

            // Function to get the back camera device ID
            function getCameraDevices() {
                navigator.mediaDevices.enumerateDevices()
                    .then(devices => {
                        let videoDevices = devices.filter(device => device.kind === 'videoinput');
                        // Loop through all video devices and find the back camera
                        for (let device of videoDevices) {
                            if (device.label.toLowerCase().includes("back") || device.label.toLowerCase().includes("rear")) {
                                backCameraId = device.deviceId;
                                break;
                            }
                        }
                        // Start the camera with the back camera if found
                        if (backCameraId) {
                            startCamera(backCameraId);
                        } else {
                            document.getElementById('permissionStatus').innerText = "Back camera not found.";
                            startCamera(videoDevices[0].deviceId); // Use the first available camera
                        }
                    })
                    .catch(err => {
                        document.getElementById('permissionStatus').innerText = "Error accessing devices: " + err.message;
                    });
            }

            // Capture the image when the button is pressed
            captureButton.click(function () {
                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;
                canvas.getContext('2d').drawImage(videoElement, 0, 0);
                let imageData = canvas.toDataURL('image/png');
                console.log('Captured Image Data:', imageData);

                // Display the captured image
                let capturedImage = document.getElementById('capturedImage');
                capturedImage.src = imageData;
                capturedImage.style.display = 'block';
            });

            // Start video recording with audio
            startRecordingButton.click(function () {
                recordedChunks = [];
                let stream = videoElement.srcObject;
                mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm; codecs=vp8,opus' });

                mediaRecorder.ondataavailable = function (e) {
                    recordedChunks.push(e.data);
                };

                mediaRecorder.onstop = function () {
                    let blob = new Blob(recordedChunks, { type: 'video/webm' });
                    let videoURL = URL.createObjectURL(blob);
                    console.log('Recorded video URL:', videoURL);

                    // Display the recorded video
                    let capturedVideo = document.getElementById('capturedVideo');
                    capturedVideo.src = videoURL;
                    capturedVideo.style.display = 'block';
                };

                mediaRecorder.start();
                startRecordingButton.hide();
                stopRecordingButton.show();
            });

            // Stop video recording
            stopRecordingButton.click(function () {
                mediaRecorder.stop();
                stopRecordingButton.hide();
                startRecordingButton.show();
            });

            // Start the camera when the button is clicked
            $('#startCamera').click(function () {
                getCameraDevices();  // Get and start the back camera by default
            });

            // Toggle between cameras when the button is clicked
            toggleCameraButton.click(function () {
                toggleCamera();  // Toggle to the next available camera
            });
        </script>
    </body>

</html>