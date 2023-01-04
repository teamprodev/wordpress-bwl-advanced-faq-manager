;(function () {
  let baf_container = "ac-container" // parent container class.

  if (document.getElementsByClassName(baf_container).length > 0) {
    let logOutput = false // Output schema to console. For Dev.

    let questionClass = "baf_schema" // Question Class.
    let answerClass = "baf_content" // Answer Class.

    // Build Data
    let questions = Array.from(document.getElementsByClassName(questionClass)).map(function (e) {
      return e.textContent
    })

    let answers = Array.from(document.getElementsByClassName(answerClass)).map(function (e) {
      return e.textContent
    })

    if (questions.length && answers.length) {
      let data = {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        mainEntity: [],
      }

      buildItem = (q, a) => {
        // Ref: https://developers.google.com/search/docs/advanced/structured-data/faqpage
        let item = {
          "@type": "Question",
          name: null,
          acceptedAnswer: {
            "@type": "Answer",
            text: null,
          },
        }

        item["name"] = q
        item["acceptedAnswer"]["text"] = a

        return item
      }

      data["mainEntity"] = questions.map(function (q, i) {
        // Here we go for the extra checking.
        // if any
        if (q.trim().length != 0 && answers[i].trim().length != 0) {
          return buildItem(q, answers[i])
        }
      })

      let script = document.createElement("script") // create a brand new elements.
      script.type = "application/ld+json"
      script.innerHTML = JSON.stringify(data) // convert string data in to json format.
      document.getElementsByTagName("head")[0].appendChild(script)

      if (logOutput) {
        console.assert(questions.length == answers.length, {
          questions: questions.length,
          answers: answers.length,
          errorMsg: "Questions and Answers are not the same lengths",
        })
        console.log(script.outerHTML)
      }
    }
  }
})(document)
