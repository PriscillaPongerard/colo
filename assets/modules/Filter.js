/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLElement} sorting
 * @property {HTMLFormElement} form
 */
export default class Filter{
  /**
   * @param {HTMLElement|null} element
   */
  constructor(element) {
    if (element === null) {
      return
    }
    this.pagination = element.querySelector('.js-filter-pagination')
    this.content = element.querySelector('.js-filter-content')
    this.sorting = element.querySelector('.js-filter-sorting')
    this.form = element.querySelector('.js-filter-form')
    this.bindEvents()
  }

  /**
   * ajout les comportement
   */
  bindEvents() {
    this.sorting.addEventListener('click', e => {
      if (e.target.tagName === 'a') {
        e.preventDefault()
        this.loadUrl(e.target.getAttribute('href'))
      }
    })
    this.form.querySelectorAll('input').forEach((input) => {
      input.addEventListener('keyup', this.loadForm.bind(this))
    })
  }

  async loadForm() {
    const data = new FormData(this.form)
    //recupere l'action dans l'url
    const url = new URL(
      this.form.getAttribute('action') || window.location.href,
    )
    const params = new URLSearchParams()
    data.forEach((value, key) => {
      params.append(key, value)
    })
    // debugger pour verifié si je recuppere la lettre saisie dans le form (params.toString())
    return this.loadUrl(url.pathname + '?' + params.toString())
  }

  async loadUrl(url) {
    //pour dire au nav qu'il ne renvoit pas une page json si retour
    const ajaxUrl = url + '&ajax=1'
    const response = await fetch(ajaxUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
    if (response.status >= 200 && response.status < 300) {
      // recupere les donnes en json
      const data = await response.json()
      //renvoye la reponse dans le contenu
      this.content.innerHTML = data.content
      history.replaceState({}, '', url)
    } else {
      console.error(response)
    }
  }
}
