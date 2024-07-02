function makeid() { //empty result
    document.getElementById("output-final").innerHTML = '';
    let result = ' ';
    let list = "";
    let passwords = '';
    const shuffle = v => [...v].sort(_ => Math.random() - .5).join('');
    const no_of_times = document.getElementById('passwords').value;
    for (let k = 0; k < no_of_times; k++) {
        // result = '';
        var characters = '';
        if (document.getElementById('uppercase').checked == true)

            characters += "ABCDEFGHIJKLMNOPRSTUVWXYZ";

        if (document.getElementById('lowercase').checked == true)
            characters += "abcdefghijklmnopqrstuvwxyz";

        if (document.getElementById('number_id').checked == true)
            characters = "0123456789" + characters;
        if (document.getElementById('char_id').checked == true)
            characters = "!@#$%^&*()_+~|<>?|" + characters;

        console.log(characters)
        const charactersLength = document.getElementById('lenght').value;

        for (let i = 0; i < charactersLength; i++) {
            characters = shuffle(characters);
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            console.log(result)

        }
        let div = document.createElement("div")
        let span = document.createElement('span');
        span.classList.add("text-danger", "d-inline-block")
        span.innerText = result; //result saved

        div.appendChild(span)
        div.classList.add("my-0", "p-3", "border-bottom")
        document.getElementById('output-final').appendChild(div);
        passwords += result + ",";
        result = "";

    }

    document.getElementById("passes").value = passwords;
    // var element = document.querySelector("#save_col_div");
    $("#save_col_div").css("display", "block");
    // element.classList.remove("d-none", "d-js-block");
}

