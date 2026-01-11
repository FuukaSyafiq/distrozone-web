<style>
    .wrapper {
        max-width: 65ch;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .call-to-action-text {
        margin: 2rem 0;
        text-align: left;
    }

    .star-wrap {
        width: max-content;
        margin: 0 auto;
        position: relative;
    }

    .star-label.hidden {
        display: none;
    }

    .star-label {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 4rem;
        height: 4rem;
    }

    @media (min-width: 840px) {
        .star-label {
            width: 6rem;
            height: 6rem;
        }
    }

    .star-shape {
        background-color: gold;
        width: 80%;
        height: 80%;
        /*star shaped cutout, works  best if it is applied to a square*/
        /* from Clippy @ https://bennettfeely.com/clippy/ */
        clip-path: polygon(50% 0%,
                61% 35%,
                98% 35%,
                68% 57%,
                79% 91%,
                50% 70%,
                21% 91%,
                32% 57%,
                2% 35%,
                39% 35%);
    }

    /* make stars *after* the checked radio gray*/
    .star:checked+.star-label~.star-label .star-shape {
        background-color: lightgray;
    }

    /*hide away the actual radio inputs*/
    .star {
        position: fixed;
        opacity: 0;
        /*top: -90000px;*/
        left: -90000px;
    }

    .star:focus+.star-label {
        outline: 2px dotted black;
    }

    .skip-button {
        display: block;
        width: 2rem;
        height: 2rem;
        border-radius: 1rem;
        position: absolute;
        top: -2rem;
        right: -1rem;
        /*transform: translateY(-50%);*/
        text-align: center;
        line-height: 2rem;
        font-size: 2rem;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .skip-button:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    #skip-star:checked~.skip-button {
        display: none;
    }

    #result {
        text-align: center;
        padding: 1rem 2rem;
    }

    .exp-link {
        text-align: center;
        padding: 1rem 2rem;
    }

    .exp-link a {
        color: lightgray;
        text-decoration: underline;
    }
</style>
<!-- Your Form -->
<form action="/" method="POST">
    @csrf
    <h1>Select a rating:</h1>
    <div class="star-wrap">
        <input class="star" checked type="radio" value="-1" id="skip-star" name="star-radio" autocomplete="off" />
        <label class="star-label hidden"></label>
        <input class="star" type="radio" id="st-1" value="1" name="star-radio" autocomplete="off" />
        <label class="star-label" for="st-1">
            <div class="star-shape"></div>
        </label>
        <input class="star" type="radio" id="st-2" value="2" name="star-radio" autocomplete="off" />
        <label class="star-label" for="st-2">
            <div class="star-shape"></div>
        </label>
        <input class="star" type="radio" id="st-3" value="3" name="star-radio" autocomplete="off" />
        <label class="star-label" for="st-3">
            <div class="star-shape"></div>
        </label>
        <input class="star" type="radio" id="st-4" value="4" name="star-radio" autocomplete="off" />
        <label class="star-label" for="st-4">
            <div class="star-shape"></div>
        </label>
        <input class="star" type="radio" id="st-5" value="5" name="star-radio" autocomplete="off" />
        <label class="star-label" for="st-5">
            <div class="star-shape"></div>
        </label>
        <label class="skip-button" for="skip-star">&times;</label>
    </div>
    <button type="submit">Submit</button>
</form>

<!-- Script at the bottom of the body -->
{{-- <script>
    console.log("ha")

    function submitted(event) {
        event.preventDefault(); // Prevent default form submission

        // Access the radio buttons correctly
        const selectedStar = document.querySelector('input[name="star-radio"]:checked');

        console.log(selectedStar);
        if (selectedStar) {
            const starVal = selectedStar.value;

            if (starVal == -1) {
                alert("You did not choose a rating.");
            } else {
                alert("You chose: " + starVal + " star(s).");

                // Submit the form if you want to proceed with the rating
                // form.submit(); // Optional: Uncomment to submit after the alert
            }
        } else {
            alert("Please select a rating.");
        }
    }
</script> --}}
