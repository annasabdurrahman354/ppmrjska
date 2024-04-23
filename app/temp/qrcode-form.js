function onScanSuccess(decodedText, decodedResult) {
    // handle the scanned code as you like, for example:
    alert(`Mengirim Idaa = ${decodedText} `);
    var scanButton = document.getElementById("scanButton");
    scanButton.setAttribute('wire:click', `dispatchFormEvent('qr::scanned', ${decodedText})`);
    alert(`Mengirim Idaa = ${decodedText} `);
    scanButton.click(); // this will trigger the click event
};

function onScanFailure(error) {
    // handle scan failure, usually better to ignore and keep scanning.
    // for example:
    // console.warn(`Terjadi kesalahan sistem QR Code Scanner = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {fps: 10},
);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);

