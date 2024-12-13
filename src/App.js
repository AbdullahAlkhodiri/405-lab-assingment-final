import React, { useState, useEffect } from 'react';
import './style.css';

const apiUrl = 'http://localhost:3001/api';

function BookmarkApp() {
  const [bookmarks, setBookmarks] = useState([]);
  const [newTitle, setNewTitle] = useState('');
  const [newLink, setNewLink] = useState('');

  useEffect(() => {
    fetchAllBookmarks();
  }, []);

  const fetchAllBookmarks = async () => {
    const response = await fetch(apiUrl + '/readAll.php');
    const data = await response.json();
    if (Array.isArray(data)) {
      setBookmarks(data);
    } else {
      setBookmarks([]);
    }
  };

  const addNewBookmark = async () => {
    if (newTitle.trim() && newLink.trim()) {
      const options = {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ title: newTitle, link: newLink }),
      };

      await fetch(apiUrl + '/create.php', options);
      setNewTitle('');
      setNewLink('');
      fetchAllBookmarks();
    }
  };

  const deleteBookmark = async (id) => {
    const data = { id };
    const options = {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    };
    await fetch(apiUrl + '/delete.php', options);
    fetchAllBookmarks();
  };

  const handleTitleChange = (e) => {
    setNewTitle(e.target.value);
  };

  const handleLinkChange = (e) => {
    setNewLink(e.target.value);
  };

  return (
    <div>
      <h1>Bookmark Manager</h1>
      <div id="add-bookmark-form">
        <input type="text" id="new-title" value={newTitle} onChange={handleTitleChange} placeholder="Bookmark title"/>
        <input type="text" id="new-link" value={newLink} onChange={handleLinkChange} placeholder="Bookmark link" />
        <button id="add-btn" onClick={addNewBookmark}>Add Bookmark</button>
      </div>

      <ul id="bookmarkList">
        {Array.isArray(bookmarks) && bookmarks.length > 0 ? (
          bookmarks.map((bookmark) => (
            <li key={bookmark.id}>
              <a href={bookmark.link} target="_blank">{bookmark.title}</a>
              <span className="span-btns" > <span id={bookmark.id} title="delete" onClick={() => deleteBookmark(bookmark.id)} > X </span></span>
            </li>
          ))):
          ( 
          <li>No bookmarks found</li>
            )}
      </ul>
    </div>
  );
}

export default BookmarkApp;
