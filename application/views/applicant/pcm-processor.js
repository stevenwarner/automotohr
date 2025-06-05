class PCMProcessor extends AudioWorkletProcessor {
    constructor() {
    super();
    
    // Initialize state
    this._buffer = new Float32Array(0);
    this._bufferSize = 4096; // Larger buffer for smoother playback
    
    // Set up message handling for new PCM data
    this.port.onmessage = (event) => {
        if (event.data.type === 'pcm') {
        this.processPCMData(event.data.data);
        }
    };
    }
    
    processPCMData(data) {
    // Convert Int16Array to Float32Array for audio processing
    const floatData = new Float32Array(data.length);
    for (let i = 0; i < data.length; i++) {
        // Normalize to range [-1.0, 1.0] with slight scaling to prevent clipping
        floatData[i] = (data[i] / 32768.0) * 0.95;
    }
    
    // Create new buffer with enough space for existing and new data
    const newBuffer = new Float32Array(this._buffer.length + floatData.length);
    
    // Copy existing data
    newBuffer.set(this._buffer, 0);
    
    // Add new data
    newBuffer.set(floatData, this._buffer.length);
    
    // Update buffer
    this._buffer = newBuffer;
    }
    
    process(inputs, outputs, parameters) {
    const output = outputs[0];
    const channel = output[0];
    
    // If we have enough data in the buffer
    if (this._buffer.length >= channel.length) {
        // Copy data from buffer to output
        for (let i = 0; i < channel.length; i++) {
        channel[i] = this._buffer[i];
        }
        
        // Remove used data from buffer
        this._buffer = this._buffer.slice(channel.length);
        
        // Apply subtle low-pass filter to reduce high-frequency noise
        let prevSample = 0;
        const FILTER_COEFF = 0.05; // Low value = subtle filtering
        for (let i = 0; i < channel.length; i++) {
        channel[i] = (1 - FILTER_COEFF) * channel[i] + FILTER_COEFF * prevSample;
        prevSample = channel[i];
        }
    } else {
        // Not enough data yet, fill with zeros
        for (let i = 0; i < channel.length; i++) {
        channel[i] = 0;
        }
    }
    
    // Keep processor alive
    return true;
    }
}

registerProcessor('pcm-processor', PCMProcessor);