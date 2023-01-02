function getFormData(elementClass, sessionStorageKey, customKeyValueObject) {
  const formData = new FormData();
  const elements = document.getElementsByClassName(elementClass);

  Array.from(elements).map((element) => {
    if (element.type === "radio") {
      if (element.checked) {
        formData.append(element.name, element.value);
      }
    } else if (element.type === "datetime-local") {
      const ISOString = (new Date(element.value)).toISOString();
      const formattedDate = ISOString.replace('T', ' ');
      const formattedDate2 = formattedDate.replace('.000Z', '');

      formData.append(element.name, formattedDate2);
    } else {
      formData.append(element.name, element.value);
    }
  });

  if (sessionStorageKey) {
    if (Array.isArray(sessionStorageKey)) {
      sessionStorageKey.map(eachKey => {
        const sessionStorageValue = sessionStorage.getItem(eachKey);

        formData.append(eachKey, sessionStorageValue);
      });
    } else {
      const sessionStorageValue = sessionStorage.getItem(sessionStorageKey);

      formData.append(sessionStorageKey, sessionStorageValue);
    }
  }

  if (customKeyValueObject) {
    // customKeyValueObject -> {input_name : value}
    if (Object.keys(customKeyValueObject).length > 1) {
      Object.keys(customKeyValueObject).map((customKey) => {
        formData.append(customKey, customKeyValueObject[customKey]);
      });
    } else {
      formData.append(
        Object.keys(customKeyValueObject)[0],
        Object.values(customKeyValueObject)[0]
      );
    }
  }
  
  return formData;
}

function getInputValue(elementClass) {
  const inputKeyValueObject = {};
  const elements = document.getElementsByClassName(elementClass);

  Array.from(elements).map((element) => {
    if (element.type === "radio") {
      if (element.checked) {
        inputKeyValueObject[element.name] = element.value;
      }
    } else {
      inputKeyValueObject[element.name] = element.value;
    }
  });
  
  return inputKeyValueObject;
}
