// $(document).ready(function () {
//     loginId = 'DC-MOHD8340'; // use your login ID.
//     product = 'DIRECTRTLITE';
//     apikey = '05546177F7514194AC29'; // use your API Key

//     $.get("https://whitegoldtrades.com/generate/token").then((res) => {
//         console.log(JSON.parse(res).AccessToken);
//         var wsEndPoint = `ws://116.202.165.216:992/directrt/?loginid=${loginId}&accesstoken=${JSON.parse(res).AccessToken}&product=${product}`
//         console.log(wsEndPoint);
//     })
// });