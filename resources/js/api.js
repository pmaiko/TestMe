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

export const login = async (data) => {
  return window.axios({
    url: '/login',
    method: 'POST',
    data: removeEmptyFields(data)
  })
}

export const register = async (data) => {
  return window.axios({
    url: '/register',
    method: 'POST',
    data: removeEmptyFields(data)
  })
}

export const user = async () => {
  return window.axios({
    url: '/user',
    method: 'GET'
  })
}

export const logout = async () => {
  return window.axios({
    url: '/logout',
    method: 'POST'
  })
}

export const tests = async () => {
  return window.axios({
    url: '/tests',
    method: 'GET'
  })
}

export const testCreate = async (data) => {
  return window.axios({
    url: '/test',
    method: 'POST',
    data: removeEmptyFields(data)
  })
}

export const testUpdate = async (data) => {
  return window.axios({
    url: '/test',
    method: 'PUT',
    data: removeEmptyFields(data)
  })
}

export const testDelete = async (data) => {
  return window.axios({
    url: '/test',
    method: 'DELETE',
    data: removeEmptyFields(data)
  })
}


// eslint-disable-next-line camelcase
export const test = async (test_id) => {
  return window.axios({
    // eslint-disable-next-line camelcase
    url: `/test/${test_id}`,
    method: 'GET'
  })
}

export const questionCreate = async (data) => {
  return window.axios({
    url: '/question',
    method: 'POST',
    data: removeEmptyFields(data)
  })
}

export const questionUpdate = async (data) => {
  return window.axios({
    url: '/question',
    method: 'PUT',
    data: removeEmptyFields(data)
  })
}

export const questionDelete = async (data) => {
  return window.axios({
    url: '/question',
    method: 'DELETE',
    data: removeEmptyFields(data)
  })
}

