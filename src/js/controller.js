//Variables
const sectionTwo = document.querySelector(".section-two");
const sectionOne = document.querySelector(".section-one");
const inputSearch = document.querySelector(".input--search");
const btnSearch = document.querySelector(".btn--search");
const btnSubmit = document.querySelector(".btn--submit");

//timeout function
const timeOut = function (s) {
  return new Promise(function (_, reject) {
    setTimeout(function () {
      reject(new Error(`Request Took Too Long! Timeout after ${s} second`));
    }, s * 1000);
  });
};

//object
const atad = {
  page: 1,
  records: [],
};

//function to display more details about a restaurant
const showRestaurant = async function () {
  try {
    const id = window.location.hash.slice(1);

    if (!id) return;

    const res = await fetch(
      `http://localhost/findr/api/restaurants/read_one.php?id=${id}`
    );

    const data = await res.json();

    if (!res.ok) throw new Error(`${data.message} (${res.status})`);

    console.log(data);

    //rendering restaurant
    const markup = `
    <img src="${data.image_url}" alt="Restaurant Image" class="bg-img" />
    <div class="bg">
      <h1>${data.name}</h1>
      <p>
   ${data.address}
      </p>
      <p>${data.city}</p>
      <p>
       ${data.about}
      </p>
      <a href="${data.website}">Their Website</a>
    </div>
    `;
    sectionTwo.innerHTML = "";
    sectionTwo.insertAdjacentHTML("afterbegin", markup);
  } catch (err) {
    throw err;
  }
};

["hashchange", "load"].forEach((ev) =>
  window.addEventListener(ev, showRestaurant)
);

const getSearchResultsPage = function (page = atad.page) {
  atad.page = page;
  const start = (page - 1) * 5; //0
  const end = page * 5; //5
  console.log(atad.records.slice(start, end));
  return atad.records.slice(start, end);
};

//function to get all restaurants in a particular city
const search = async function (query) {
  try {
    if (inputSearch.value) {
      query = inputSearch.value;
      const res = await fetch(
        `http://localhost/findr/api/restaurants/search.php?s=${query}`
      );

      const data = await res.json();

      atad.records = data.records;

      if (!res.ok) throw new Error(`${data.message} (${res.status})`);

      sectionOne.innerHTML = "";

      getSearchResultsPage().forEach((e) => {
        let markup = `
        <a href="#${e.id}" class="list">
        <figure>
        <img src="${e.image_url}" />
        </figure>
        <figcaption>${e.name}</figcaption>
        </a>
        `;
        sectionOne.insertAdjacentHTML("afterbegin", markup);
      });

      paginate();
    }
  } catch (err) {
    console.log(err);
    sectionTwo.innerHTML = "";
    sectionTwo.innerHTML =
      '<h1 class="center">we are working on adding more cities. try searching for another one.</h1>';
  }
};

//To render the paginate buttons based on the results
function paginate() {
  const numPages = Math.ceil(atad.records.length / 5);
  console.log(numPages);

  //if there is only one page
  if (atad.page === 1 && numPages < 2) {
    return;
  }

  if (atad.page === 1 && numPages > 1) {
    let markup = `  <div class="paginate">
    <button data-goto="${
      atad.page + 1
    }" class="right btn next">Page 2 &rarr;</button>
  </div> `;
    sectionOne.insertAdjacentHTML("afterbegin", markup);

    document.querySelector(".right").addEventListener("click", function () {
      const goToPage = +document.querySelector(".right").dataset.goto;
      sectionOne.innerHTML = "";
      getSearchResultsPage(goToPage).forEach((e) => {
        let markup = `
        <a href="#${e.id}" class="list">
        <figure>
        <img src="${e.image_url}" />
        </figure>
        <figcaption>${e.name}</figcaption>
        </a>
        `;
        sectionOne.insertAdjacentHTML("afterbegin", markup);
      });
      paginate();
    });
    return;
  }

  if (atad.page !== numPages && numPages > 1) {
    let markup = `  <div class="paginate">
    <button data-goto="${atad.page - 1}" class="left btn next">Page ${
      atad.page - 1
    } &larr;</button>
    <button data-goto="${atad.page + 1}" class="right btn next">Page ${
      atad.page + 1
    } &rarr;</button>
  </div> `;
    sectionOne.insertAdjacentHTML("afterbegin", markup);

    document.querySelector(".right").addEventListener("click", function () {
      const goToPage = +document.querySelector(".right").dataset.goto;
      sectionOne.innerHTML = "";
      getSearchResultsPage(goToPage).forEach((e) => {
        let markup = `
        <a href="#${e.id}" class="list">
        <figure>
        <img src="${e.image_url}" />
        </figure>
        <figcaption>${e.name}</figcaption>
        </a>
        `;
        sectionOne.insertAdjacentHTML("afterbegin", markup);
      });
      paginate();
    });

    document.querySelector(".left").addEventListener("click", function () {
      const goToPage = +document.querySelector(".left").dataset.goto;
      sectionOne.innerHTML = "";
      getSearchResultsPage(goToPage).forEach((e) => {
        let markup = `
        <a href="#${e.id}" class="list">
        <figure>
        <img src="${e.image_url}" />
        </figure>
        <figcaption>${e.name}</figcaption>
        </a>
        `;
        sectionOne.insertAdjacentHTML("afterbegin", markup);
      });
      paginate();
    });
    return;
  }

  // last page
  if (atad.page === numPages && numPages > 1) {
    let markup = `  <div class="paginate">
    <button data-goto="${atad.page - 1}" class="left btn next">&larr; Page ${
      atad.page - 1
    }</button>
  </div> `;
    sectionOne.insertAdjacentHTML("afterbegin", markup);

    document.querySelector(".left").addEventListener("click", function () {
      const goToPage = +document.querySelector(".left").dataset.goto;
      sectionOne.innerHTML = "";
      getSearchResultsPage(goToPage).forEach((e) => {
        let markup = `
        <a href="#${e.id}" class="list">
        <figure>
        <img src="${e.image_url}" />
        </figure>
        <figcaption>${e.name}</figcaption>
        </a>
        `;
        sectionOne.insertAdjacentHTML("afterbegin", markup);
      });
      paginate();
    });
    return;
  }
}

//To submit query whether the user clicks enter or the search button
document.querySelector(".x").addEventListener("submit", function (e) {
  e.preventDefault();
  search();
});
