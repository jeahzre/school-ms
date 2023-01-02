_global_ = {
  ..._global_,
  data: [],
  pageNumber: 1,
  totalPage: null,
  numberOfEntries: "All",
  multiplicationOf: 2,
  // From defined _global_ in each file:
  // item: null, // Page name like grade, class.
  // itemIdName: null, // In order for composite key in 'tr' tag
  // listModelFile: null, // Without extension
  // itemKeysForEdit: [],
  // optionKeys: [] // input 'name' attribute value that has options rendered in 'select' element.
};

function capitalize(s) {
  return s[0].toUpperCase() + s.slice(1);
}

function getElementCompositeId(action) {
  let elementIdArr = [];

  _global_["itemIdName"].map((itemId) => {
    const eachIdValue = document.querySelector(`#${itemId}`).value;
    elementIdArr.push(eachIdValue);
  });

  const elementId = elementIdArr.join("_");

  return elementId;
}

function formatData(dataArr) {
  const formattedData = {};

  Object.entries(dataArr).map(([key, value]) => {
    if (_global_["capitalizeValueKeys"].includes(key)) {
      formattedData[key] = capitalize(value);
    } else {
      formattedData[key] = value;
    }
  });

  return formattedData;
}

function modifyGlobalData(type, idToModify, modifiedData) {
  if (type === "add") {
    _global_["data"].push(modifiedData);
  } else if (type === "edit") {
    if (Array.isArray(_global_["itemIdName"])) {
      const itemToEditIndex = _global_["data"].findIndex((data) => {
        const eachIdMatch = [];

        _global_["itemIdName"].map((itemId) => {
          if (data[itemId] !== idToModify[itemId]) {
            eachIdMatch.push(false);
          }

          eachIdMatch.push(true);
        });
        const result = eachIdMatch.every((idMatch) => idMatch === true);

        return result;
      });
      _global_["data"][itemToEditIndex] = modifiedData;
    } else {
      const itemToEditIndex = _global_["data"].findIndex((data) => {
        const eachId = data[_global_["itemIdName"]];

        if (eachId === idToModify) {
          return true;
        } else {
          return false;
        }
      });

      _global_["data"][itemToEditIndex] = modifiedData;
    }
  } else if (type === "delete") {
    if (Array.isArray(_global_["itemIdName"])) {
      const idToModifyArr = idToModify.split("_");
      const newGlobalData = _global_["data"].filter((data) => {
        const eachIdMatch = []; // [true, true, ...]

        _global_["itemIdName"].map((itemId, itemIdIndex) => {
          const eachId = data[itemId];
          const idToModify = idToModifyArr[itemIdIndex];

          if (eachId !== idToModify) {
            eachIdMatch.push(true);
          } else {
            eachIdMatch.push(false);
          }
        });
        const result = eachIdMatch.some((idMatch) => idMatch === true);

        return result;
      });
      _global_["data"] = newGlobalData;
    } else {
      _global_["data"] = _global_["data"].filter((data) => {
        const eachId = data[_global_["itemIdName"]];

        if (eachId !== idToModify) {
          return true;
        } else {
          return false;
        }
      });
    }
  }
}

function convertObjectKeysToGlobalKeys(type, objectToConvert) {
  // Remove 'add_' or 'edit_' to push to global data to keep sync with it
  if (type === "edit") {
    const itemObjectInGlobalKey = {};
    Object.entries(objectToConvert).map(([key, value]) => {
      const itemKey = key.replace(/edit_/, "");
      itemObjectInGlobalKey[itemKey] = value;
    });
    return itemObjectInGlobalKey;
  } else if (type === "add") {
    const itemObjectInGlobalKey = {};
    Object.keys(objectToConvert).map((key) => {
      const itemKey = key.replace(/add_/, "");
      itemObjectInGlobalKey[itemKey] = objectToConvert[key];
    });
    return itemObjectInGlobalKey;
  }
}

function handleCloseModal(e) {
  closeModal(e);

  if (Array.isArray(_global_["itemIdName"])) {
    _global_["itemIdName"].map((itemId) => {
      removeSessionStorageItems(`before_edit_${itemId}`);
    });
  } else {
    removeSessionStorageItems(`before_edit_${_global_["itemIdName"]}`);
  }
}

function renderListRows(dataArr, fromToIndex = "All") {
  if (dataArr.length > 0) {
    function renderRows(dataArr) {
      dataArr.map((data) => {
        renderRow(data);
      });
    }

    const entriesTBodyElement = document.getElementById("entries-tbody");

    entriesTBodyElement.innerHTML = "";
    
    if (fromToIndex === "All") {
      renderRows(dataArr, "All");
    } else {
      const [fromIndex, toIndex] = fromToIndex;
      const chosenData = dataArr.filter((data, index) => {
        if (index >= fromIndex && index <= toIndex) {
          return true;
        } else {
          return false;
        }
      });

      renderRows(chosenData);
    }
  }
}

function getFromToIndex(numberOfEntries) {
  let fromToIndex = null;

  if (numberOfEntries === "All") {
    fromToIndex = "All";
  } else {
    const fromIndex = numberOfEntries * (_global_["pageNumber"] - 1);
    const toIndex = fromIndex + numberOfEntries - 1;

    fromToIndex = [fromIndex, toIndex];
  }
  
  return fromToIndex;
}

function setTotalPage() {
  if (_global_["numberOfEntries"] === "All") {
    _global_["totalPage"] = 1;
  } else {
    _global_["totalPage"] = Math.ceil(
      _global_["data"].length / _global_["numberOfEntries"]
    );
  }
}

function setGlobalPageNumber() {
  if (_global_["pageNumber"] > _global_["totalPage"]) {
    _global_["pageNumber"] = _global_["totalPage"];
  }
}

function getListData(formDataAdd) {
  const formDataObject = {
    [`get_${_global_["item"]}_list`]: `get_${_global_["item"]}_list`,
    ...formDataAdd,
  };

  function handleXMLHttpResponse(parsedData) {
    handleDataUpdate(true, parsedData);
  }

  const formData = getFormData(null, null, formDataObject);

  return requestXMLHttp(
    formData,
    _global_["listModelFile"],
    handleXMLHttpResponse,
    ["parsedData"]
  );
}

function removeSessionStorageItems(sessionStorageItems) {
  if (Array.isArray(sessionStorageItems)) {
    for (const sessionStorageItem of sessionStorageItems) {
      sessionStorage.removeItem(sessionStorageItem);
    }
  } else {
    sessionStorage.removeItem(sessionStorageItems);
  }
}

function addItem(e) {
  if (e) {
    e.preventDefault();
  }

  const formData = getFormData(`add_${_global_["item"]}`);

  function handleXMLHttpResponse(parsedData) {
    // If server return true, put input value to table
    const newItemObject = getInputValue(`add_${_global_["item"]}`);
    const itemObjectInGlobalKey = convertObjectKeysToGlobalKeys(
      "add",
      newItemObject
    );

    Object.entries(parsedData).map(([key, value]) => {
      itemObjectInGlobalKey[key] = value;
    });
    modifyGlobalData("add", null, itemObjectInGlobalKey);
    renderRow(itemObjectInGlobalKey);
    handleDataUpdate();
  }

  requestXMLHttp(formData, _global_["listModelFile"], handleXMLHttpResponse, [
    "parsedData",
  ]);
}

function setInSessionStorage(
  format,
  objectThatHasId,
  isToFindInObject = false
) {
  if (Array.isArray(_global_["itemIdName"])) {
    _global_["itemIdName"].map((itemId) => {
      let formattedItemId = "";

      if (format) {
        formattedItemId = `${format}_${itemId}`;
      } else {
        formattedItemId = itemId;
      }

      sessionStorage.setItem(
        `before_edit_${itemId}`,
        objectThatHasId[formattedItemId]
      );
    });
  } else {
    const itemId = _global_["itemIdName"];
    let formattedItemId = "";

    if (format) {
      formattedItemId = `${format}_${itemId}`;
    } else {
      formattedItemId = itemId;
    }

    if (isToFindInObject) {
      // objectThatHasId = {id: 1, name: ''}
      sessionStorage.setItem(
        `before_edit_${_global_["itemIdName"]}`,
        objectThatHasId[formattedItemId]
      );
    } else {
      // objectThatHasId = 2
      sessionStorage.setItem(
        `before_edit_${_global_["itemIdName"]}`,
        objectThatHasId
      );
    }
  }
}

function setBeforeEditItem(e, editedId) {
  let idValue = null; // {} or string
  let recordScopeElement = null;

  if (!editedId) {
    // If user hasn't edited and hasn't set 'before_edit_{_global_['itemIdName']}' in session storage
    if (_global_["tableType"] === "key-value") {
      recordScopeElement = document.getElementById("detail-edit-table");
    } else {
      recordScopeElement = e.target.closest("tr");
    }

    if (Array.isArray(_global_["itemIdName"])) {
      const idValueObject = {};

      _global_["itemIdName"].map((itemId) => {
        let eachIdValue;

        if (_global_["tableType"] === "key-value") {
          eachIdValue = recordScopeElement.getElementById(`${itemId}_value`);
        } else {
          eachIdValue = recordScopeElement.querySelector(
            `.${itemId}`
          ).innerText;
        }

        idValueObject[itemId] = eachIdValue;
      });

      idValue = idValueObject;
    } else {
      if (_global_["tableType"] === "key-value") {
        idValue = document.getElementById(`${_global_["itemIdName"]}_key`);
      } else {
        idValue = recordScopeElement.querySelector(
          `.${_global_["itemIdName"]}`
        ).innerText;
      }
    }
  }

  // else {
  //   // If user has edited and has set 'before_edit_{_global_['itemIdName']}' in session storage
  //   if (Array.isArray(_global_["itemIdName"])) {
  //     const elementId = getElementCompositeId();
  //     trElement = document.getElementById(elementId);
  //     idValue = elementId;
  //   } else {
  //     trElement = document.getElementById(editedId);
  //     idValue = editedId;
  //   }
  // }

  const beforeEditItem = {};

  _global_["itemKeysForEdit"].map((key) => {
    let fieldElement;

    if (_global_["tableType"] === "key-value") {
      fieldElement = document.getElementById(`${key}_value`);
    } else {
      fieldElement = recordScopeElement.querySelector(`.${key}`);
    }

    const value = fieldElement.dataset.value;

    beforeEditItem[key] = value;
  });

  setInSessionStorage(null, idValue);

  function setModalInputValue(beforeEditItem) {
    Object.entries(beforeEditItem).map(([key, val]) => {
      // Set input value in edit modal
      const editInputElements = document.getElementsByName(`edit_${key}`);
      const editInputElement = editInputElements[0];

      if (editInputElements.length > 1) {
        if (editInputElement.type === "radio") {
          const radioInputElement = document.getElementById(`edit_${val}`);

          radioInputElement.checked = true;
        }
      } else if ((editInputElements.length = 1)) {
        editInputElement.value = val;
      }
    });
  }

  setModalInputValue(beforeEditItem);
}

function startEditItem(e) {
  // Edit class: set session storage of before edit class name, class name to edit, and student count to edit
  openModal(e);
  getOptions(e, "edit");
  // Pick table value to input element on modal to edit
  setBeforeEditItem(e);
}

function startAddItem(e) {
  openModal(e);
  getOptions(e, "add");
}

function renderEditedItemRow(beforeEditId, editedClassObject) {
  function getCompositeId(format, objectThatHasId) {
    const compositeIdArr = [];

    _global_["itemIdName"].map((itemId) => {
      let formattedProperty = "";

      if (format) {
        formattedProperty = `${format}_${itemId}`;
      } else {
        formattedProperty = itemId;
      }

      const idValue = objectThatHasId[formattedProperty];

      compositeIdArr.push(idValue);
    });

    const compositeId = compositeIdArr.join("_");

    return compositeId;
  }

  function setElementId() {
    let afterEditElementId = "";

    if (Array.isArray(_global_["itemIdName"])) {
      afterEditElementId = getCompositeId("edit", editedClassObject);
    } else {
      afterEditElementId = editedClassObject[`edit_${_global_["itemIdName"]}`];
    }

    rowElementToEdit.id = afterEditElementId;
  }

  function setRowData() {
    _global_["itemKeysForEdit"].map((key) => {
      let value = editedClassObject[`edit_${key}`];
      let elementToEdit;

      if (_global_["tableType"] === "key-value") {
        elementToEdit = document.querySelector(`#${key}_value`);
        elementToEdit.dataset.dataId = value;
      } else {
        elementToEdit = rowElementToEdit.querySelector(`.${key}`);
      }

      if(key === 'datetime') {
        value = changeDateStringtoDatetimeLocalString(value);
      }

      if (
        _global_["capitalizeValueKeys"] &&
        _global_["capitalizeValueKeys"].includes(key)
      ) {
        const formattedValue = capitalize(value);

        elementToEdit.innerText = formattedValue;
      } else {
        elementToEdit.innerText = value;
      }

      elementToEdit.dataset.value = value;
    });
  }

  function getBeforeEditElementId() {
    let beforeEditElementId = "";

    if (typeof beforeEditId === "object" && beforeEditId !== null) {
      beforeEditElementId = getCompositeId(null, beforeEditId);
    } else {
      beforeEditElementId = beforeEditId;
    }

    return beforeEditElementId;
  }

  let beforeEditElementId;
  let rowElementToEdit;

  if (_global_["tableType"] === "key-value") {
    rowElementToEdit = document.getElementById("table-id");
    beforeEditElementId = rowElementToEdit.dataset[_global_["itemIdName"]];
  } else {
    // else if (_global_["tableType"] === "table") {
    beforeEditElementId = getBeforeEditElementId();
    rowElementToEdit = document.getElementById(beforeEditElementId);
  }

  // setElementId();
  setRowData();
}

function submitEditItem() {
  let beforeEditItemName = null;

  if (Array.isArray(_global_["itemIdName"])) {
    let beforeEditItemNameArr = [];

    _global_["itemIdName"].map((itemId) => {
      beforeEditItemNameArr.push(`before_edit_${itemId}`);
    });
    beforeEditItemName = beforeEditItemNameArr;
  } else {
    beforeEditItemName = `before_edit_${_global_["itemIdName"]}`;
  }

  const formData = getFormData(`edit_${_global_["item"]}`, beforeEditItemName);

  function handleXMLHttpResponse() {
    // If server return true, change table value with input value from modal
    let beforeEditIdNameValue; // [] or string

    if (Array.isArray(_global_["itemIdName"])) {
      const beforeEditIdNameValueObject = {};

      _global_["itemIdName"].map((itemId) => {
        beforeEditIdNameValueObject[itemId] = sessionStorage.getItem(
          `before_edit_${itemId}`
        );
      });
      beforeEditIdNameValue = beforeEditIdNameValueObject;
    } else {
      beforeEditIdNameValue = sessionStorage.getItem(
        `before_edit_${_global_["itemIdName"]}`
      );
    }

    const editedItemObject = getInputValue(`edit_${_global_["item"]}`);
    const editedItemObjectInGlobalKeys = convertObjectKeysToGlobalKeys(
      "edit",
      editedItemObject
    );

    modifyGlobalData(
      "edit",
      beforeEditIdNameValue,
      editedItemObjectInGlobalKeys
    );
    renderEditedItemRow(beforeEditIdNameValue, editedItemObject);
    setInSessionStorage("edit", editedItemObject, true);
    // setBeforeEditItem(
    //   null,
    //   editedItemObjectInGlobalKeys[_global_["itemIdName"]]
    // );
  }

  requestXMLHttp(formData, _global_["listModelFile"], handleXMLHttpResponse);
}

function deleteListRow(idName) {
  const trElementToDelete = document.getElementById(idName);

  trElementToDelete.remove();
}

function deleteItem(e) {
  const trElement = e.target.closest("tr");
  let idValue = "";

  if (Array.isArray(_global_["itemIdName"])) {
    idValue = trElement.id;
  } else {
    idValue = trElement.querySelector(`.${_global_["itemIdName"]}`).innerText;
  }

  let formDataObject = {};

  if (Array.isArray(_global_["itemIdName"])) {
    const itemIdValueArr = idValue.split("_");

    _global_["itemIdName"].map((itemId, index) => {
      formDataObject[`delete_${itemId}`] = itemIdValueArr[index];
    });
  } else {
    formDataObject = {
      [`delete_${_global_["itemIdName"]}`]: idValue,
    };
  }

  const formData = getFormData(null, null, formDataObject);

  function handleXMLHttpResponse(idValue) {
    modifyGlobalData("delete", idValue);
    deleteListRow(idValue);
    handleDataUpdate();
  }

  requestXMLHttp(formData, _global_["listModelFile"], handleXMLHttpResponse, [
    idValue,
  ]);
}

function renderNavPageNumberBtns() {
  const navPageNumbersElement = document.getElementById("nav-page-numbers");

  navPageNumbersElement.innerHTML = "";

  for (let i = 1; i <= _global_["totalPage"]; i++) {
    const navButton = document.createElement("button");

    navButton.dataset.navigatePage = i;
    navButton.onclick = function () {
      navigatePage(null, i);
    };
    navButton.innerHTML = `${i}`;
    navPageNumbersElement.appendChild(navButton);
  }
}

function setAndRenderListRows(e) {
  let numberOfEntries = null;

  if (e) {
    // If button has been rendered, set based on user input and change global. Convert string input to number if character in string is number.
    numberOfEntries = e.currentTarget.value;

    if (numberOfEntries !== "All") {
      _global_["numberOfEntries"] = Number(numberOfEntries);
      numberOfEntries = Number(numberOfEntries);
    } else {
      _global_["numberOfEntries"] = String(numberOfEntries);
    }
  } else {
    // If button hasn't been rendered, use default in global.
    numberOfEntries = _global_["numberOfEntries"];

    if (numberOfEntries !== "All") {
      numberOfEntries = Number(_global_["numberOfEntries"]);
    } else {
      numberOfEntries = String(_global_["numberOfEntries"]);
    }
    // Default number of entries is 'All' and current page number is 1
  }

  handleDisplayUpdate();

  const fromToIndex = getFromToIndex(numberOfEntries);

  renderListRows(_global_["data"], fromToIndex);
}

function searchRowsByIdName(e) {
  const inputValue = e.target.value;

  if (inputValue) {
    function processSearchValue(inputValue) {
      let processedSearchValue;
      const stringifiedInputValue = String(inputValue);

      processedSearchValue = stringifiedInputValue.trim();
      processedSearchValue = processedSearchValue.toLowerCase();

      return processedSearchValue;
    }

    const processedSearchValue = processSearchValue(inputValue);
    const entriesTBodyElement = document.getElementById("entries-tbody");
    let chosenDataArr = [];

    if (Array.isArray(_global_["itemIdName"])) {
      // If item has multiple IDs
      const newChosenDataArr = _global_["data"].filter((data) => {
        const eachIdMatch = []; // [true, true, ...]

        _global_["itemIdName"].map((itemId) => {
          let eachId;

          if (
            _global_["hiddenIdNameKeyValue"] &&
            _global_["hiddenIdNameKeyValue"][itemId]
          ) {
            const nameKey = _global_["hiddenIdNameKeyValue"][itemId];

            eachId = data[nameKey];
          } else {
            eachId = data[itemId];
          }

          const processedEachId = processSearchValue(eachId);

          if (processedEachId.includes(processedSearchValue)) {
            eachIdMatch.push(true);
          } else {
            eachIdMatch.push(false);
          }
        });

        const result = eachIdMatch.some((idMatch) => idMatch === true);

        return result;
      });

      chosenDataArr = newChosenDataArr;
    } else {
      const newChosenDataArr = _global_["data"].filter((data) => {
        const eachId = data[_global_["itemIdName"]];
        const processedEachId = processSearchValue(eachId);

        if (processedEachId === processedSearchValue) {
          return true;
        } else {
          return false;
        }
      });

      chosenDataArr = newChosenDataArr;
    }

    entriesTBodyElement.innerHTML = "";

    if (chosenDataArr.length > 0) {
      renderListRows(chosenDataArr);
    } else if (chosenDataArr.length === 0) {
      renderListRows(_global_["data"]);
    }
  } else {
    // Render all rows
    renderListRows(_global_["data"]);
  }
}

function navigatePage(e, dataset) {
  let navigateTo = null;

  if (e) {
    navigateTo = e.currentTarget.dataset.navigatePage;
  } else {
    navigateTo = dataset;
  }

  if (navigateTo === "next-page") {
    if (_global_["pageNumber"] !== _global_["totalPage"]) {
      _global_["pageNumber"]++;
    }
  } else if (navigateTo === "previous-page") {
    if (_global_["pageNumber"] !== 1) {
      _global_["pageNumber"]--;
    }
  } else if (typeof navigateTo == "number") {
    if (navigateTo !== _global_["pageNumber"]) {
      _global_["pageNumber"] = navigateTo;
    }
  }

  handleDisplayUpdate();

  const fromToIndex = getFromToIndex(_global_["numberOfEntries"]);

  renderListRows(_global_["data"], fromToIndex);
}

function renderNumberOfEntriesOptions(init) {
  // const numberOfEntries = _global_['multiplicationOf'];
  const selectElement = document.getElementById("number-of-entries-select");

  selectElement.innerHTML = "";

  const allOptionElement = document.createElement("option");

  allOptionElement.value = "All";
  allOptionElement.innerHTML = "All";

  if (init) {
    allOptionElement.selected = true;
  }

  selectElement.appendChild(allOptionElement);

  for (
    let i = _global_["multiplicationOf"];
    i < _global_["data"].length;
    i += _global_["multiplicationOf"]
  ) {
    const optionElement = document.createElement("option");

    optionElement.value = i;
    optionElement.innerHTML = i;
    selectElement.appendChild(optionElement);
  }
}

function renderEntriesCountDescription() {
  const fromIndexElement = document.getElementById("from-index");
  const toIndexElement = document.getElementById("to-index");
  const allNumberEntriesElement = document.getElementById("all-number-entries");
  const entriesCountDetailElement = document.getElementById(
    "entries-count-detail"
  );
  const globalNumberOfEntriesElement = document.getElementById(
    "global-number-of-entries"
  );

  if (_global_["numberOfEntries"] === "All") {
    entriesCountDetailElement.classList.remove("show");
    allNumberEntriesElement.classList.add("show");
  } else {
    allNumberEntriesElement.classList.remove("show");
    entriesCountDetailElement.classList.add("show");
    
    let fromIndex,
      toIndex = null;

    fromIndex =
      _global_["numberOfEntries"] * _global_["pageNumber"] -
      (_global_["numberOfEntries"] - 1);
    toIndex = _global_["numberOfEntries"] * _global_["pageNumber"];

    if (_global_["data"].length < toIndex) {
      toIndex = _global_["data"].length;
    }

    fromIndexElement.innerHTML = fromIndex;
    toIndexElement.innerHTML = toIndex;
    globalNumberOfEntriesElement.innerHTML = _global_["data"].length;
  }
}

function renderOptions(e, options, action) {
  const beforeEditItem = {};

  if (action === "edit") {
    const trElement = e.target.closest("tr");

    _global_["optionKeys"].map((optionKey) => {
      const tdElement = trElement.querySelector(`.${optionKey}`);
      const tdElementValue = tdElement.dataset.value;

      beforeEditItem[optionKey] = tdElementValue;
    });
  }

  const elements = {};

  _global_["optionKeys"].map((optionKey) => {
    elements[optionKey] = document.getElementById(`${action}_${optionKey}`);
  });

  Object.entries(options).map(([optionKey, value]) => {
    const selectElement = elements[optionKey];

    selectElement.innerHTML = "";
    // value -> [{id:..., name: ...}, {id:..., name: ...}, ...]

    value.map((option) => {
      const optionElement = getOptionElement(option, optionKey);

      selectElement.appendChild(optionElement);
    });

    // Set selected option
    if (action === "add") {
      selectElement.firstElementChild.selected = true;
    } else if (action === "edit") {
      selectElement.value = beforeEditItem[optionKey];
    }
  });
}

function setHiddenInputsValue(action) {
  const addModal = document.querySelector("#add-item-modal");
  const hasSelectInput = addModal.querySelector("select") !== null;

  if (hasSelectInput) {
    Object.entries(_global_["hiddenIdNameKeyValue"]).map(([key, value]) => {
      const referencedElement = document.getElementById(`${action}_${key}`);
      const referencingElement = document.getElementById(`${action}_${value}`);
      const selectedIndex = referencedElement.selectedIndex;
      const selectedOption =
        referencedElement[selectedIndex] || referencedElement.value;
      
      referencingElement.value = selectedOption.innerText || selectedOption;
    });
  }
}

function getOptions(e, action) {
  const formDataObject = {
    get_option_list: "get_option_list",
  };

  function handleXMLHttpResponse(parsedData) {
    renderOptions(e, parsedData, action);

    if (action === "add") {
      setHiddenInputsValue("add");
    }
  }

  const formData = getFormData(null, null, formDataObject);

  return requestXMLHttp(
    formData,
    _global_["listModelFile"],
    handleXMLHttpResponse,
    ["parsedData"]
  );
}

function handleSelectChange(e) {
  const modal = e.target.closest(".modal").id;

  if (modal === "add-item-modal") {
    setHiddenInputsValue("add");
  } else {
    setHiddenInputsValue("edit");
  }
}

function handleDisplayUpdate() {
  // Must in order v
  setTotalPage();
  setGlobalPageNumber();
  // ^
  renderEntriesCountDescription();
  renderNavPageNumberBtns();
}

function handleDataUpdate(init, initData) {
  if (init) {
    _global_["data"] = initData;
    setAndRenderListRows(null);
  }

  // Must in rder v
  setTotalPage();
  setGlobalPageNumber();
  // ^
  renderEntriesCountDescription();
  renderNumberOfEntriesOptions(init);
  renderNavPageNumberBtns();
}

function specifyIDOnInput() {
  const searchInputElement = document.getElementById("search_item_by_id");

  if (Array.isArray(_global_["itemIdName"])) {
    searchInputElement.placeholder = _global_["itemIdName"].join(", ");
  } else {
    searchInputElement.placeholder = _global_["itemIdName"];
  }
}

function changeDateStringtoDatetimeLocalString(dateString) {
  const date = (new Date(dateString));
  // Function link: https://stackoverflow.com/a/17415677 (the function has been modified here)

  const pad = function (num) {
    return (num < 10 ? "0" : "") + num;
  };

  return (
    date.getFullYear() +
    "-" +
    pad(date.getMonth() + 1) +
    "-" +
    pad(date.getDate()) +
    " " +
    pad(date.getHours()) +
    ":" +
    pad(date.getMinutes()) +
    ":" +
    pad(date.getSeconds())
  );
}

function changeYYYYMMDDHHMMDateFormatToISOString(datetime) {
  const dateString = datetime.replace(' ', 'T');
  const dateISOString = dateString + '.000Z';

  return dateISOString;
}

window.addEventListener("DOMContentLoaded", () => {
  // Request data then render it
  getListData();
  // Specify by what user can search item
  specifyIDOnInput();
});
