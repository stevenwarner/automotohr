/**
 * Record Video
 *
 * @param {Object} opt (Options)
 */
function msVideoRecorder(opt) {
	/**
	 * Stores media source instance
	 */
	this.mediaSource = undefined;
	/**
	 * Stores media recorder instance
	 */
	this.mediaRecorder = undefined;
	/**
	 * Stores stream as blobs
	 */
	this.recordedBlobs = [];
	/**
	 * Stores source buffer
	 */
	this.sourceBuffer = undefined;
	/**
	 * Wether the video is playing or not
	 */
	this.isRecording = false;
	/**
	 * Saves the reference to capturing
	 * video
	 */
	this.sourceVideo = document.getElementById(`${opt.recorderPlayer}`);
	/**
	 * Saves the reference to preview
	 * video
	 */
	this.targetVideo = document.getElementById(`${opt.previewPlayer}`);
	/**
	 * Saves reference to start record
	 */
	this.recordButton = document.getElementById(`${opt.recordButton}`);
	/**
	 * Saves reference to play button
	 */
	this.playButton = document.getElementById(`${opt.playRecordedVideoBTN}`);
	/**
	 * Saves reference to pause button
	 */
	this.pauseRecordingButton = document.getElementById(
		`${opt.pauseRecordedVideoBTN}`
	);
	/**
	 * Saves reference to resume button
	 */
	this.resumeRecordingButton = document.getElementById(
		`${opt.resumeRecordedVideoBTN}`
	);
	/**
	 * Saves reference to remove button
	 */
	this.removeButton = document.getElementById(
		`${opt.removeRecordedVideoBTN}`
	);

	this.errorSectionReference = document.getElementById(
		opt.errorSectionReference
	);

	/**
	 * Start/Stop the video record process
	 */
	this.toggleRecording = () => {
		if (this.isRecording === false) {
			this.isRecording = true;
			this.startRecording();
		} else {
			this.stopRecording();
			this.isRecording = false;
			if (this.playButton) this.playButton.disabled = false;

			this.targetVideo.parentElement.classList.remove("hidden");
			this.targetVideo.parentElement.parentElement.classList.remove(
				"hidden"
			);
		}
	};

	/**
	 * Start the video recording
	 */
	this.startRecording = () => {
		let options = { mimeType: "video/webm", bitsPerSecond: 10000000 };
		this.recordedBlobs = [];
		try {
			this.mediaRecorder = new MediaRecorder(window.stream, options);
		} catch (e0) {
			console.log(
				"Unable to create MediaRecorder with options Object: ",
				e0
			);
			try {
				options = {
					mimeType: "video/webm,codecs=vp9",
					bitsPerSecond: 100000,
				};
				this.mediaRecorder = new MediaRecorder(window.stream, options);
			} catch (e1) {
				console.log(
					"Unable to create MediaRecorder with options Object: ",
					e1
				);
				try {
					options = "video/vp8";
					this.mediaRecorder = new MediaRecorder(
						window.stream,
						options
					);
				} catch (e2) {
					alert(
						"MediaRecorder is not supported by this browser.\n\n" +
							"Try Firefox 29 or later, or Chrome 47 or later, with Enable experimental Web Platform features enabled from chrome://flags."
					);
					console.error(
						"Exception while creating MediaRecorder:",
						e2
					);
					return;
				}
			}
		}
		this.recordButton.innerHTML =
			'<i class="fa fa-stop-circle"></i> Stop Recording';
		if (this.playButton) this.playButton.disabled = true;
		this.mediaRecorder.onstop = this.handleStop;
		this.mediaRecorder.ondataavailable = this.handleDataAvailable;
		this.mediaRecorder.start(10);
		this.pauseRecordingButton.classList.remove("hidden");
		this.resumeRecordingButton.classList.add("hidden");
	};

	/**
	 * Stop the video recording
	 */
	this.stopRecording = () => {
		this.removeButton.classList.remove("hidden");
		this.playButton.classList.remove("hidden");
		this.pauseRecordingButton.classList.add("hidden");
		this.recordButton.innerHTML =
			'<i class="fa fa-stop"></i> Start Recording';
		this.mediaRecorder.stop();
		this.targetVideo.controls = true;
		this.play();
	};

	/**
	 * Pause the recorded video
	 */
	this.pauseRecording = () => {
		this.pauseRecordingButton.classList.add("hidden");
		this.resumeRecordingButton.classList.remove("hidden");
		this.mediaRecorder.pause();
	};

	/**
	 * Resume the recorded video
	 */
	this.resumeRecording = () => {
		this.resumeRecordingButton.classList.add("hidden");
		this.pauseRecordingButton.classList.remove("hidden");
		this.mediaRecorder.resume();
	};

	/**
	 * Handle buffer
	 */
	this.handleSourceOpen = () => {
		sourceBuffer = this.mediaSource.addSourceBuffer(
			'video/webm; codecs="vp8"'
		);
		console.log(sourceBuffer);
	};

	/**
	 * Handle recorded blobs
	 */
	this.handleDataAvailable = (event) => {
		if (event.data && event.data.size > 0) {
			this.recordedBlobs.push(event.data);
		}
		window.recordedBlobs = this.recordedBlobs;
	};

	/**
	 * Handle permission error
	 *
	 * @param {Object} error
	 */
	this.errorCallback = (error) => {
		this.errorSectionReference.innerHTML = `
			<p class="alert alert-danger"><strong>Please, allow microphone and camera access to record the video.</strong></p>
		`;
		// hide the record button
		console.log("navigator.getUserMedia error: ", error);
	};

	/**
	 * Handle stream object
	 *
	 * @param {Oject} stream
	 */
	this.successCallback = (stream) => {
		window.stream = stream;
		this.mediaSource = new MediaSource();
		this.mediaSource.addEventListener("sourceopen", this.handleSourceOpen);
		//
		this.recordButton.onclick = this.toggleRecording;
		this.pauseRecordingButton.onclick = this.pauseRecording;
		this.resumeRecordingButton.onclick = this.resumeRecording;
		if (this.playButton) this.playButton.onclick = this.play;
		if (this.removeButton) this.removeButton.onclick = this.remove;
		this.sourceVideo.srcObject = stream;
		this.sourceVideo.play();
		//
		this.recordButton.classList.remove("hidden");
	};

	/**
	 * Handle recorder stop event
	 *
	 * @param {Object} event
	 */
	this.handleStop = (event) => {
		console.log("Recorder stopped: ", event);
	};

	/**
	 * Get recorded video
	 */
	this.getVideo = () => {
		return new Promise((res) => {
			const reader = new FileReader();
			reader.onload = function (event) {
				res(event.target.result);
			};
			reader.readAsDataURL(
				new Blob(this.recordedBlobs, { type: "video/webm" })
			);
		});
	};

	/**
	 * Play the recorded video
	 */
	this.play = () => {
		const superBuffer = new Blob(this.recordedBlobs, {
			type: "video/webm",
		});
		this.targetVideo.src = window.URL.createObjectURL(superBuffer);
		this.targetVideo.play();
	};

	/**
	 * Remove the recorded video
	 */
	this.remove = () => {
		this.isRecording = false;
		this.targetVideo.src = null;
		this.recordedBlobs = [];
		this.playButton.classList.add("hidden");
		this.removeButton.classList.add("hidden");
	};

	/**
	 * Stop the recorded video
	 */
	this.stop = () => {
		this.targetVideo.stop();
	};

	/**
	 * Close the active stream
	 */
	this.close = () => {
		this.isRecording = false;
		this.pauseRecordingButton.classList.add("hidden");
		this.resumeRecordingButton.classList.add("hidden");
		if (window.stream !== undefined) {
			window.stream.getTracks().forEach(function (track) {
				if (track.readyState == "live") {
					track.stop();
				}
			});
		}
	};

	/**
	 * Start the initial process
	 */
	this.init = () => {
		//
		const isSecureOrigin =
			location.protocol === "https:" || location.host === "localhost";
		if (!isSecureOrigin) {
			// alert('getUserMedia() must be run from a secure origin: HTTPS or localhost.' +
			//     '\n\nChanging protocol to HTTPS');
			// location.protocol = 'HTTPS';
		}
		//
		navigator.getUserMedia =
			navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia;
		//
		const constraints = {
			audio: true,
			video: true,
		};
		//
		navigator.getUserMedia(
			constraints,
			this.successCallback,
			this.errorCallback
		);
	};
}
