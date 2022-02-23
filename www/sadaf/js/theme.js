function changeBackground(color) {
    document.body.style.backgroundColor = color;
}

function changeFont(font) {
    document.body.style.fontFamily = font;
}

function changeFontSize(fontSize) {
    document.body.style.fontSize = fontSize + 'px';
}

function changeFontColor(color) {
    document.body.style.color = color;
}

function changeStatus(status) {
    // if (status === "online") {
    //     parent.document.getElementById("menu-statusIcon").style.color = 'rgb(28, 240, 28)';    
    // }
    // else if (status === "busy") {
    //     parent.document.getElementById("menu-statusIcon").style.color = 'rgb(240, 28, 39)';    
    // }
    // else {
    //     parent.document.getElementById("menu-statusIcon").style.color = 'rgba(232, 255, 28, 0.993)';    
    // }
}

window.addEventListener("load", function() { 
        let color1 = localStorage.getItem("color1");
        let font = localStorage.getItem("font");
        let fontSize = localStorage.getItem("font_size");        
        let color = localStorage.getItem("color3");
        // for (var i = 0; i < localStorage.length; i++){
        //     console.log(localStorage.getItem(localStorage.key(i)));
        // }

        console.log(color1, font, fontSize, status);
        changeBackground(color1);
        changeFontColor(color);
        changeFont(font);
        changeFontSize(fontSize);
    }
);

