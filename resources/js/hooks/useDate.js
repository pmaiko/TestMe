export const useFormattedDate = (dateString) => {
  const dateObject = new Date(dateString)
  const formattedDate = dateObject.toLocaleDateString()
  const formattedTime = dateObject.toLocaleTimeString()

  const year = dateObject.getFullYear()
  const month = String(dateObject.getMonth() + 1).padStart(2, '0')
  const day = String(dateObject.getDate()).padStart(2, '0')
  const hours = String(dateObject.getHours()).padStart(2, '0')
  const minutes = String(dateObject.getMinutes()).padStart(2, '0')
  const seconds = String(dateObject.getSeconds()).padStart(2, '0')

  const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`

  return {
    formattedDate,
    formattedTime,
    formattedDateTime
  }
}
