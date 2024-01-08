function removeEmptyFields (obj) {
  return obj
  // const cleanedObj = {}
  //
  // for (const key in obj) {
  //   if (obj.hasOwnProperty(key)) {
  //     const value = obj[key]
  //
  //     if (value !== '' && value !== null && value !== undefined) {
  //       if (typeof value === 'object' && !Array.isArray(value)) {
  //         const nestedCleanedObj = removeEmptyFields(value)
  //         if (Object.keys(nestedCleanedObj).length > 0) {
  //           cleanedObj[key] = nestedCleanedObj
  //         }
  //       } else {
  //         cleanedObj[key] = value
  //       }
  //     }
  //   }
  // }
  //
  // return cleanedObj
}


class Api {
  login = async (data) => {
    return window.axios({
      url: '/login',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  register = async (data) => {
    return window.axios({
      url: '/register',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  user = async () => {
    return window.axios({
      url: '/user',
      method: 'GET'
    })
  }

  logout = async () => {
    return window.axios({
      url: '/logout',
      method: 'POST'
    })
  }

  tests = async () => {
    return window.axios({
      url: '/tests',
      method: 'GET'
    })
  }

  testCreate = async (data) => {
    return window.axios({
      url: '/test',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  testUpdate = async (data) => {
    return window.axios({
      url: '/test/put',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  testDelete = async (data) => {
    return window.axios({
      url: '/test/delete',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  // eslint-disable-next-line camelcase
  test = async (test_id, params) => {
    return window.axios({
      // eslint-disable-next-line camelcase
      url: `/test/${test_id}`,
      method: 'GET',
      params
    })
  }

  // eslint-disable-next-line camelcase
  testing = async (test_id, params) => {
    return window.axios({
      // eslint-disable-next-line camelcase
      url: `/test/${test_id}/testing`,
      method: 'GET',
      params: params || {}
    })
  }

  completeTest = async (testId, data) => {
    return window.axios({
      // eslint-disable-next-line camelcase
      url: `/test/${testId}/complete`,
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  question = async (questionId) => {
    return window.axios({
      url: `/question/${questionId}`,
      method: 'GET'
    })
  }

  questionCreate = async (data) => {
    return window.axios({
      url: '/question',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  questionUpdate = async (data) => {
    return window.axios({
      url: '/question/put',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  questionDelete = async (data) => {
    return window.axios({
      url: '/question/delete',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  // results
  getResultsTests = async (data) => {
    return window.axios({
      url: '/results/tests',
      method: 'GET',
      data: removeEmptyFields(data)
    })
  }

  getResultsAttempts = async (testId) => {
    return window.axios({
      url: `/results/${testId}`,
      method: 'GET'
    })
  }

  getResultsDashboard = async (testId) => {
    return window.axios({
      url: `/results/${testId}/dashboard`,
      method: 'GET'
    })
  }

  getTestAttempt = async (testId, attempt) => {
    return window.axios({
      url: `/results/${testId}/${attempt}`,
      method: 'GET'
    })
  }

  deleteTestAttempt = async (testId, attempt) => {
    return window.axios({
      url: `/results/${testId}/${attempt}/delete`,
      method: 'POST'
    })
  }

  setAnswer = async (data) => {
    return window.axios({
      url: '/results/set-answer',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  // favorites
  favorites = async () => {
    return window.axios({
      url: '/favorites',
      method: 'GET'
    })
  }

  addFavorite = async (data) => {
    return window.axios({
      url: '/favorite',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }

  deleteFavorite = async (data) => {
    return window.axios({
      url: '/favorite/delete',
      method: 'POST',
      data: removeEmptyFields(data)
    })
  }
}


export const useApi = () => {
  return new Api()
}
