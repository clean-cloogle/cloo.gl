definition module cloogl

from Data.Error import :: MaybeErrorString, :: MaybeError

/*
 * Shorten a url with the cloo.gl url shortener
 * 
 * @param url
 * @param timeout in ms
 * @param World
 */
cloogl :: String Int *World -> (MaybeErrorString String, *World)
