export const login = async (data) => {
  return window.axios({
    url: '/login',
    method: 'POST',
    data
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
