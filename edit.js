// 創建新的 input 元素
function addInput() {
    // 創建新的 input 元素
    var newInput = document.createElement('input');
    
    // 設置 input 的屬性
    newInput.type = 'text';
    newInput.name = 'dynamicInput';
    
    // 將 input 添加到容器中
    var inputContainer = document.getElementById('Words_part');
    inputContainer.appendChild(newInput);
    // inputContainer.appendChild(document.createElement('br'));    // 看需不需要換行
}


// // 啟用按鈕
// function enableButton() {
//     // 獲取按鈕元素的引用
//     var myButton = document.getElementById('word');
//     myButton.disabled = false;
// }

// 禁用按鈕
// function disableButton() {
//     // 獲取按鈕元素的引用
//     var myButton = document.getElementById('word');
//     myButton.disabled = true;
// }
