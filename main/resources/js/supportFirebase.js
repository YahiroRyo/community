import firebase from 'firebase'

const getUidAndToken = async() => {
    return new Promise(async(resolve, reject) => {
        let result = {
            isError:    false,
            token:      null,
            uid:        null,
        }
        await firebase.auth().onAuthStateChanged(async(user) => {
            result.uid = user.uid
            if (user) {
                await user.getIdToken().then((token) => {
                    result.token = token
                })
                .catch(() => {
                    result.isError = true
                    reject(result)
                })
            } else {
                result.isError = true
                reject(result)
            }
            resolve(result)
        })
    })
}

export { getUidAndToken } 