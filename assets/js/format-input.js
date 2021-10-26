// ====================Format rupiah==========================
const getInputRupiah = document.getElementsByClassName("rupiah");

for (let i = 0; i < getInputRupiah.length; i++) {
    getInputRupiah[i].setAttribute("autocomplete", "off");

    getInputRupiah[i].addEventListener('keyup', function (e) {
        if (onlyNumber(getInputRupiah[i].value) === false) {
            e.preventDefault();
        } else {
            getInputRupiah[i].value = formatRupiah(this.value, 'Rp. ');
        }
    });
}


const formatRupiah = (data, prefix) => {
    let dataString = data.replace(/[^,\d]/g, '').toString(),
        split = dataString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik kedalam angka ketika telah menjadi ribuan
    if (ribuan) {
        separator = sisa ? '.' : ''; //if(sisa){'.'}else{''}
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

// format rupiah text
const rupiahText = document.getElementsByClassName("rupiahText");
for (let x = 0; x < rupiahText.length; x++) {
    rupiahText[x].innerHTML = formatRupiah(rupiahText[x].innerHTML, 'Rp. ');
    // console.log(rupiahText[x].innerHTML);
}

// only number 1 
function onlyNumber(evt) {
    const charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

// only number 2
const onlyNumberSecoundary = (data) => {
    let char = String.fromCharCode(data.which);
    if (!(/[0-9]/.test(char))) {
        alert('only number !!');
        data.preventDefault();
    }
}

// disabled copy paste
const disasbleCopyPaste = (e) => {
    let key = event.keyCode || e.charCode;
    if (key == 17 || key == 2) {
        alert('sdgdgds');
        return false;
    }
}

// input type disable typing
window.onload = () => {
    const getDisableNumber = document.getElementsByClassName("d-typing");
    for (let i = 0; i < getDisableNumber.length; i++) {
        getDisableNumber[i].addEventListener("keypress", (event) => {
            event.preventDefault();
        });
    }
}
const getNumber = document.getElementsByClassName("disable-typing");
for (let i = 0; i < getNumber.length; i++) {
    getNumber[i].addEventListener("keypress", (event) => {
        event.preventDefault();
    });
}