/**
 * SCORM API Adaptor for 1.2
 *
 * @author  AutomotoHR <www.automotohr.com>
 * @version 1.0
 */

// set the adaptor object
const SCORM_12_API = {
	// set last error index
	lastError: "0",
	// set error codes
	errorCodes: {
		"No error": "No error occurred, the previous API call was successful.",
		"General Exception":
			"No specific error code exists to describe the error. Use LMSGetDiagnostic for more information.",
		"Invalid argument error":
			"Indicates that an argument represents an invalid data model element or is otherwise incorrect.",
		"Element cannot have children":
			"Indicates that LMSGetValue was called with a data model element name that ends in “_children” for a data model element that does not support the “_children” suffix.",
		"Element not an array. Cannot have count.":
			"Indicates that LMSGetValue was called with a data model element name that ends in “_count” for a data model element that does not support the “_count” suffix.",
		"Not initialized":
			"Indicates that an API call was made before the call to LMSInitialize.",
		"Not implemented error":
			"The data model element indicated in a call to LMSGetValue or LMSSetValue is valid, but was not implemented by this LMS. SCORM 1.2 defines a set of data model elements as being optional for an LMS to implement.",
		"Invalid set value, element is a keyword":
			"Indicates that LMSSetValue was called on a data model element that represents a keyword (elements that end in “_children” and “_count”).",
		"Element is read only.":
			"LMSSetValue was called with a data model element that can only be read.",
		"Element is write only":
			"LMSGetValue was called on a data model element that can only be written to.",
		"Incorrect Data Type":
			"LMSSetValue was called with a value that is not consistent with the data format of the supplied data model element.",
	},
	// set the CMI elements - data model
	CMIElements: {
		"cmi.core.student_id": "",
		"cmi.core.student_name": "",
		"cmi.core.lesson_location": "",
		"cmi.core.credit": "credit", // “credit”, “no-credit”
		"cmi.core.lesson_status": "not attempted", // “passed”, “completed”, “failed”, “incomplete”, “browsed”, “not attempted”
		"cmi.core.entry": "", // “ab-initio”, “resume”, “”,
		"cmi.core.score.raw": "",
		"cmi.core.score.max": "",
		"cmi.core.score.min": "",
		"cmi.core.total_time": "0",
		"cmi.core.lesson_mode": "normal", // “browse”, “normal”, “review”,
		"cmi.core.exit": "", // “time-out”, “suspend”, “logout”, “”
		"cmi.core.session_time": "0",
		"cmi.suspend_data": "",
		"cmi.launch_data": "",
		"cmi.comments": "",
		"cmi.comments_from_lms": "",
		"cmi.objectives._children": "", // id, score (raw, max, min), status
		"cmi.student_data.mastery_score": "", // CMIDecimal
		"cmi.student_data.max_time_allowed": "", // CMITimeSpan
		"cmi.student_data.time_limit_action": "", // "exit,message”, “exit,no message”, ”continue,message”, “continue, no message”
		"cmi.student_preference.audio": "",
		"cmi.student_preference.language": "",
		"cmi.student_preference.speed": "",
		"cmi.student_preference.text": "",
		"cmi.interactions._children": "", // id, objectives, time, type, correct_responses, weighting, student_response, result, latency
		"cmi.interactions._count": "", //
	},
	// SCORM required functions
	LMSInitialize: function () {
		return true;
	},
	LMSCommit: function () {
		// send the course
		sendCourseToSave(this.CMIElements);
		//
		return true;
	},
	LMSFinish: function () {
		return true;
	},
	LMSGetValue: function (CMIElement) {
		//
		this.lastError = "0";
		//
		if (this.CMIElements[CMIElement] === undefined) {
			this.lastError = "Invalid argument error";
		}
		//
		return this.CMIElements[CMIElement];
	},
	LMSSetValue: function (CMIElement, value) {
		//
		this.lastError = "0";
		//
		this.CMIElements[CMIElement] = value;
		//
		// send the course
		// sendCourseToSave(this.CMIElements);
		return this.CMIElements[CMIElement];
	},
	LMSGetLastError: function () {
		return this.lastError;
	},
	LMSGetErrorString: function () {
		return this.errorCodes[this.lastError];
	},
	LMSGetDiagnostic: function (CMIErrorCode) {
		return this.errorCodes[CMIErrorCode];
	},
};

// check if the course object is set
// if defined we need to overwrite CMIElement
// defaults
console.log(CMIElementsObj)
if (typeof CMIElementsObj !== "undefined") {
	SCORM_12_API.CMIElements = Object.assign(
		{},
		SCORM_12_API.CMIElements,
		CMIElementsObj
	);
}

// available adaptor to window object
window.API = SCORM_12_API;
