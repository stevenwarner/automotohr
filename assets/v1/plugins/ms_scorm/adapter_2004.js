/**
 * SCORM API Adaptor for 1.2
 *
 * @author  AutomotoHR <www.automotohr.com>
 * @version 1.0
 */

// set the adaptor object
const SCORM_2004_API = {
	// set last error index
	lastError: "0",
	// set error codes
	errorCodes: {
		"No Error": " No error occurred, the previous API call was successful.",
		"General Exception":
			" No specific error code exists to describe the error. Use GetDiagnostic for more information.",
		"General Initialization Failure":
			" Call to Initialize failed for an unknown reason.",
		"Already Initialized":
			" Call to Initialize failed because Initialize was already called.",
		"Content Instance Terminated":
			" Call to Initialize failed because Terminate was already called.",
		"General Termination Failure":
			" Call to Terminate failed for an unknown reason.",
		"Termination Before Initialization":
			" Call to Terminate failed because it was made before the call to Initialize.",
		"Termination After Termination":
			" Call to Terminate failed because Terminate was already called.",
		"Retrieve Data Before Initialization":
			" Call to GetValue failed because it was made before the call to Initialize.",
		"Retrieve Data After Termination":
			" Call to GetValue failed because it was made after the call to Terminate.",
		"Store Data Before Initialization":
			" Call to SetValue failed because it was made before the call to Initialize.",
		"Store Data After Termination":
			" Call to SetValue failed because it was made after the call to Terminate.",
		"Commit Before Initialization":
			" Call to Commit failed because it was made before the call to Initialize.",
		"Commit After Termination":
			" Call to Commit failed because it was made after the call to Terminate.",
		"General Argument Error":
			" An invalid argument was passed to an API method (usually indicates that Initialize, Commit or Terminate did not receive the expected empty string argument.",
		"General Get Failure":
			" Indicates a failed GetValue call where no other specific error code is applicable. Use GetDiagnostic for more information.",
		"General Set Failure":
			" Indicates a failed SetValue call where no other specific error code is applicable. Use GetDiagnostic for more information.",
		"General Commit Failure":
			" Indicates a failed Commit call where no other specific error code is applicable. Use GetDiagnostic for more information.",
		"Undefined Data Model Element":
			" The data model element name passed to GetValue or SetValue is not a valid SCORM data model element.",
		"Unimplemented Data Model Element":
			" The data model element indicated in a call to GetValue or SetValue is valid, but was not implemented by this LMS. In SCORM 2004, this error would indicate an LMS that is not fully SCORM conformant.",
		"Data Model Element Value Not Initialized":
			" Attempt to read a data model element that has not been initialized by the LMS or through a SetValue call. This error condition is often reached during normal execution of a SCO.",
		"Data Model Element Is Read Only":
			" SetValue was called with a data model element that can only be read.",
		"Data Model Element Is Write Only":
			" GetValue was called on a data model element that can only be written to.",
		"Data Model Element Type Mismatch":
			" SetValue was called with a value that is not consistent with the data format of the supplied data model element.",
		"Data Model Element Value Out Of Range":
			" The numeric value supplied to a SetValue call is outside of the numeric range allowed for the supplied data model element.",
		"Data Model Dependency Not Established":
			" Some data model elements cannot be set until another data model element was set. This error condition indicates that the prerequisite element was not set before the dependent element.",
	},
	// set the CMI elements - data model
	CMIElements: {
		"cmi._version": "", // Represents the version of the data model
		"cmi.comments_from_learner._children": "", // (comment,location,timestamp, RO) Listing of supported data model elements
		"cmi.comments_from_lms._children ": "", // (comment,location,timestamp, RO) Listing of supported data model elements
		"cmi.completion_status ": "not attempted", // (“completed”, “incomplete”, “not attempted”, “unknown”, RW) Indicates whether the learner has completed the SCO
		"cmi.completion_threshold ": "", // (real(10,7) range (0..1), RO) Used to determine whether the SCO should be considered complete
		"cmi.credit": "credit", // (“credit”, “no-credit”, RO) Indicates whether the learner will be credited for performance in the SCO
		"cmi.entry": "", // (ab_initio, resume, “”, RO) Asserts whether the learner has previously accessed the SCO
		"cmi.exit": "", // (timeout, suspend, logout, normal, “”, WO) Indicates how or why the learner left the SCO
		"cmi.interactions._children": "", // (id,type,objectives,timestamp,correct_responses,weighting,learner_response,result,latency,description, RO) Listing of supported data model elements
		"cmi.interactions._count": "", // (non-negative integer, RO) Current number of interactions being stored by the LMS
		"cmi.launch_data": "", // (characterstring (SPM: 4000), RO) Data provided to a SCO after launch, initialized from the dataFromLMS manifest element
		"cmi.learner_id": "", // (long_identifier_type (SPM: 4000), RO) Identifies the learner on behalf of whom the SCO was launched
		"cmi.learner_name": "", // (localized_string_type (SPM: 250), RO) Name provided for the learner by the LMS
		"cmi.learner_preference._children": "", // (audio_level,language,delivery_speed,audio_captioning, RO) Listing of supported data model elements
		"cmi.location": "", // (characterstring (SPM: 1000), RW) The learner’s current location in the SCO
		"cmi.max_time_allowed": "", // (timeinterval (second,10,2), RO) Amount of accumulated time the learner is allowed to use a SCO
		"cmi.mode": "normal", // (“browse”, “normal”, “review”, RO) Identifies one of three possible modes in which the SCO may be presented to the learner
		"cmi.objectives._children": "", // (id,score,success_status,completion_status,description, RO) Listing of supported data model elements
		"cmi.objectives._count": "", // (non-negative integer, RO) Current number of objectives being stored by the LMS
		"cmi.progress_measure": "", // (real (10,7) range (0..1), RW) Measure of the progress the learner has made toward completing the SCO
		"cmi.scaled_passing_score": "", // (real(10,7) range (-1 .. 1), RO) Scaled passing score required to master the SCO
		"cmi.score._children": "", // (scaled,raw,min,max, RO) Listing of supported data model elements
		"cmi.session_time": "", // (timeinterval (second,10,2), WO) Amount of time that the learner has spent in the current learner session for this SCO
		"cmi.success_status": "unknown", // (“passed”, “failed”, “unknown”, RW) Indicates whether the learner has mastered the SCO
		"cmi.suspend_data": "", // (characterstring (SPM: 64000), RW) Provides space to store and retrieve data between learner sessions
		"cmi.time_limit_action": "continue,no message", // (“exit,message”, “continue,message”, “exit,no message”, “continue,no message”, RO) Indicates what the SCO should do when cmi.max_time_allowed is exceeded
		"cmi.total_time": "", // (timeinterval (second,10,2), RO) Sum of all of the learner’s session times accumulated in the current learner attempt
		"adl.nav.request": "", // (request(continue, previous, choice, exit, exitAll, abandon, abandonAll, suspendAll _none_), RW) Navigation request to be processed immediately following Terminate()
	},
	// SCORM required functions
	Initialize: function () {
		return true;
	},
	Commit: function () {
		//
		return true;
	},
	Terminate: function () {
		return true;
	},
	GetValue: function (CMIElement) {
		//
		this.lastError = "0";
		//
		if (this.CMIElements[CMIElement] === undefined) {
			this.lastError = "Invalid argument error";
		}
		//
		return this.CMIElements[CMIElement];
	},
	SetValue: function (CMIElement, value) {
		//
		if (CMIElement === "cmi.completion_status" && value === "completed") {
			console.log({ CMIElements: this.CMIElements });
		}
		//
		this.lastError = "0";
		//
		this.CMIElements[CMIElement] = value;
		return this.CMIElements[CMIElement];
	},
	GetLastError: function () {
		return this.lastError;
	},
	GetErrorString: function () {
		return this.errorCodes[this.lastError];
	},
	GetDiagnostic: function (CMIErrorCode) {
		return this.errorCodes[CMIErrorCode];
	},
};

// check if the course object is set
// if defined we need to overwrite CMIElement
// defaults
if (typeof CMIElementsObj !== "undefined") {
	SCORM_2004_API.CMIElements = Object.assign(
		{},
		SCORM_2004_API.CMIElements,
		CMIElementsObj
	);
}
// available adaptor to window object
window.API_1484_11 = SCORM_2004_API;
