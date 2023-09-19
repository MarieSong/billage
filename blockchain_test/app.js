// Create NFT Form Submission
document.getElementById('createNFTForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const serialNumber = document.getElementById('serialNumber').value;
    const name = document.getElementById('name').value;
    const model = document.getElementById('model').value;
    const rentalHistory = document.getElementById('rentalHistory').value;
    const repairHistory = document.getElementById('repairHistory').value;

    await createNFT(serialNumber, name, model, rentalHistory, repairHistory);
});

// Transfer NFT Form Submission
document.getElementById('transferNFTForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const tokenId = parseInt(document.getElementById('tokenIdTransfer').value);
    const toAddress = document.getElementById('toAddress').value;
    const newRentalHistory = document.getElementById('newRentalHistory').value;
    const repairHistory = document.getElementById('newRepairHistory').value;

    await transferNFT(tokenId, toAddress, newRentalHistory, repairHistory);
});

// Get Device Data Form Submission
document.getElementById('getDeviceDataForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const tokenId = parseInt(document.getElementById('tokenIdGetData').value);

    await getDeviceData(tokenId);
});

// Get Rental History Log Form Submission
document.getElementById('getRentalHistoryLogForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const tokenId = parseInt(document.getElementById('tokenIdRentalHistory').value);

    await getRentalHistoryLog(tokenId);
});

// Get Repair History Log Form Submission
document.getElementById('getRepairHistoryLogForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const tokenId = parseInt(document.getElementById('tokenIdRepairHistory').value);

    await getRepairHistoryLog(tokenId);
});
