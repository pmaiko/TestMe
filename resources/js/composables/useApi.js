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
      url: '/test',
      method: 'PUT',
      data: removeEmptyFields(data)
    })
  }

  testDelete = async (data) => {
    return window.axios({
      url: '/test',
      method: 'DELETE',
      data: removeEmptyFields(data)
    })
  }

  // eslint-disable-next-line camelcase
  test = async (test_id) => {
    return window.axios({
      // eslint-disable-next-line camelcase
      url: `/test/${test_id}`,
      method: 'GET'
    })
  }

  // eslint-disable-next-line camelcase
  testing = async (test_id) => {
    return window.axios({
      // eslint-disable-next-line camelcase
      url: `/test/${test_id}/testing`,
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
      url: '/question',
      method: 'PUT',
      data: removeEmptyFields(data)
    })
  }

  questionDelete = async (data) => {
    return window.axios({
      url: '/question',
      method: 'DELETE',
      data: removeEmptyFields(data)
    })
  }

  getTestsWithResults = async (data) => {
    return window.axios({
      url: '/tests-with-results',
      method: 'GET',
      data: removeEmptyFields(data)
    })
  }

  getTestResults = async (testId) => {
    return window.axios({
      url: `/test-results/${testId}`,
      method: 'GET'
    })
  }
}


export const useApi = () => {
  return new Api()
}
