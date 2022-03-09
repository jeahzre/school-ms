function closeModal(e) {
  const modalType = e.target.closest('.modal').id;
  const overlayElement = document.querySelector('#overlay');
  let modalElement;
  if (modalType === 'add-item-modal') {
    modalElement = document.querySelector('#add-item-modal');
  } else {
    modalElement = document.querySelector('#edit-item-modal');
  }
  overlayElement.classList.remove('show');
  modalElement.classList.remove('show');
}

function openModal(e) {
  const modalType = e.target.dataset.modalType;
  const overlayElement = document.querySelector('#overlay');
  let modalElement;
  if (modalType === 'add-item') {
    modalElement = document.querySelector('#add-item-modal');
  } else {
    modalElement = document.querySelector('#edit-item-modal');
  }
  overlayElement.classList.add('show');
  modalElement.classList.add('show');
}