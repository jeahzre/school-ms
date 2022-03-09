function isJSON(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

function requestXMLHttp(
  formData,
  serverFileName,
  handleXMLHttpResponse,
  handleXMLHttpResponseArguments
) {
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      let parsedData = null;
      if (isJSON(this.responseText)) {
        parsedData = JSON.parse(this.responseText);
      }
      if (parsedData) {
        if (
          handleXMLHttpResponseArguments &&
          handleXMLHttpResponseArguments.some(handleXMLHttpResponseArgument => handleXMLHttpResponseArgument === "parsedData")
        ) {
          handleXMLHttpResponse(parsedData);
        } else {
          handleXMLHttpResponse(
            handleXMLHttpResponseArguments && handleXMLHttpResponseArguments[0]
          );
        }
      }
    }
  };
  xmlhttp.open("POST", `/model/${serverFileName}.php`);
  xmlhttp.send(formData);
}
