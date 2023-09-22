document.addEventListener("DOMContentLoaded", load);

async function load()
{
    PopulateItemType();
    PopulateCharacters();

    //SELECT ITEM TYPE
    document.getElementById('select-itemType').addEventListener('change', () =>
    {
      SelectItemType();
    });

    //RELOAD CHAR ITEMS
    document.getElementById('reloadItems').addEventListener('click', () =>
    {
      document.getElementById('charItems').innerHTML = '';
      SelectCharacter();
    });

    //CHAR SELECTOR
    document.getElementById('select-characters').addEventListener('change', () =>
    {
      SelectCharacter();      
    });

    //TEST BUTTON
    document.getElementById('test-button').addEventListener('click', () =>
    {
      TestServer();
    });

    //CLEAR SERVER MESSAGES
    document.getElementById('clear-button').addEventListener('click', () => 
    {
      ClearMessage();
    });
}

async function LoadLocation()
{
  const select = document.getElementById('select-characters');
  const selectedOption = select.options[select.selectedIndex];
  const charLocation = selectedOption.getAttribute('locationChar');
  const params = {charLoc: charLocation};
  const queryString = ParametrizeArray(params);

  try
  {
    const response = await fetch('http://localhost' + '/getLocation?' + queryString,
    {
      method: "GET",
      headers: {                
        "Content-Type": "application/json",
      }
    });
    const data = await response.json();    

    document.getElementById('locId').textContent = '';
    document.getElementById('locId').textContent = "Location ID: " + data.id;
    document.getElementById('locDescription').textContent = '';
    document.getElementById('locDescription').textContent = "Description: " + data.description;
  }
  catch(error)
  {
    CatchError(error);
  }
}

async function PopulateItemType()
{
    try
    {
      const response = await fetch('http://localhost' + '/getItemTypes',
      {
          method: "GET",
          headers: {                
          "Content-Type": "application/json",
          }
      });
        
        const data = await response.json();

        const select = document.getElementById('select-itemType'); // Replace with the actual ID of your select element
        const option = document.createElement('option');
        option.textContent = '';
        option.value = '';
        select.appendChild(option);
        data.forEach(item => {          
          const option = document.createElement('option');
          option.value = item.type;
          option.textContent = item.type;
          select.appendChild(option);}
        );
        select.value = '';
    }
    catch (error)
    {
      CatchError(error);
    }
}

async function LoadCharacterLocation()
{
  const charDiv = document.getElementById('character');
  
}

async function PopulateCharacters()
{
  try
  {
    const response = await fetch('http://localhost' + '/getCharacters',
    {
      method: "GET",
      headers: {                
        "Content-Type": "application/json",
      }
    });    
    const data = await response.json();

    const select = document.getElementById('select-characters');
    const initialOption = document.createElement('option');
    initialOption.textContent = '';
    initialOption.value = '';
    select.appendChild(initialOption);
    data.forEach(item =>
      {        
        const option = document.createElement('option');
        option.value = item.name;
        option.textContent = item.name;
        option.setAttribute('locationChar', item.location);
        option.setAttribute('idChar', item.id);
        select.appendChild(option);
    });
    select.value = '';
  }
  catch(error)
  {
    CatchError(error);
  }
}

async function TestServer()
{
    const par = document.getElementById('server-response');
    
    try
    {
      const response = await fetch('http://localhost' + '/test',
        {
            method: "GET",
            headers: {                
            "Content-Type": "text/plain",
            }
        });
      const data = await response.text();
      if(response.status === 200 && data.trim() !== '')
      {
        const content = document.createTextNode(data);
        par.innerHTML = '';
        par.appendChild(content);
      }
      else
      {
        const content = document.createTextNode(error);
        par.appendChild(content);
        console.error('error:', error);
      }
    }
    catch (error)
    {
      CatchError(error);
    }
}

function ClearMessage()
{
  document.getElementById('server-response').innerHTML = '';
}

async function SelectCharacter()
{
  const charSelect = document.getElementById('select-characters').value;
  if(charSelect != null && charSelect != '')
  {
    try
    {
      await LoadCharItems()
      await LoadLocation();
    }
    catch(error)
    {
      CatchError(error);
    }
  }
  else
  {
    document.getElementById('charItems').innerHTML = '';
    document.getElementById('locId').textContent = '';    
    document.getElementById('locDescription').textContent = '';
  }
}

async function SelectItemType()
{    
  const selectItemType = document.getElementById('select-itemType').value;
  let attElement = document.getElementById('attributes');
  if(selectItemType != null && selectItemType != '')
  {
    try
    {
      const response = await fetch('http://localhost' + '/getType',
      {
          method: "GET",
          headers: {                
          "Content-Type": "application/json",
          "Type": selectItemType,
          }
      });
  
      let data = await response.json();      
      let attributes = JSON.parse(data[0].attributes);

      let form = document.createElement('form');
      let mainDiv = document.createElement('div');
      form.setAttribute('id', 'form');
      mainDiv.setAttribute('id', 'values');

      attributes.forEach(attribute =>
        {
          let div = document.createElement('div');
          div.setAttribute('class', 'inputBox');
          let attributeName = Object.keys(attribute)[0];
          let attributeType = attribute[attributeName];
          
          //console.log(attribute);
          //console.log(`Attribute Name: ${attributeName}`);
          //console.log(`Attribute Type: ${attributeType}`);

          let label = document.createElement('label');
          label.textContent = attributeName;
          let input = document.createElement('input');
          input.setAttribute('class', 'inputField');
          
          if(attributeType == 'numeric')
          {
            input.type = 'number';
            input.min = 0;
            input.value = 0;
          }
          else
          {
            input.type = 'text';
          }

          div.appendChild(label);
          div.appendChild(input);
          mainDiv.appendChild(div);
        });

        attElement.appendChild(form);
        form.appendChild(mainDiv);
        const submitButton = document.createElement("input");
        submitButton.type = "submit";
        submitButton.value = "Submit";
        form.appendChild(submitButton);
    }
    catch(error)
    {
      CatchError(error);
    }
  }
  else
  {
    let form = document.getElementById('form');
    if(form != null)
    {
      form.remove();
    }
  }
}

async function LoadCharItems()
{  
  const select = document.getElementById('select-characters');
  const selectedOption = select.options[select.selectedIndex];
  const charid = selectedOption.getAttribute('idchar');

  const params = { charId: charid};
  const queryString = ParametrizeArray(params);

  try
  {
    const response = await fetch('http://localhost' + '/getCharItems?' + queryString,
    {
        method: "GET",
        headers: {                
        "Content-Type": "text/plain",
        }        
    });
    let data = await response.json();
    
    const itemArea = document.getElementById('charItems');
    if(data.length > 0)
    {      
      data.forEach(item =>
      {
        let article = document.createElement('article');
        article.setAttribute('class', 'itemArticle');

        let type = document.createElement('p');
        type.style.height = '10px';

        let drop = document.createElement('button');
        drop.textContent = 'Drop';
        drop.style.marginLeft = '5px';        
        
        let textNode = document.createTextNode("Item: " + item.type);
        type.appendChild(textNode);        
        type.appendChild(drop);

        let details = document.createElement('details');
        let id = document.createElement('p');
        let components = document.createElement('p');
        let attributes = document.createElement('p');
        let state = document.createElement('p');

        article.setAttribute('itemID', item.id);
        id.textContent = "ID: " + item.id;
        components.textContent = "Components: " + item.components;
        attributes.textContent = "Attributes: " + item.attributes;
        state.textContent = "State: " + item.state;

        article.appendChild(type);
        details.appendChild(id);
        details.appendChild(components);
        details.appendChild(attributes);
        details.appendChild(state);        
        article.appendChild(details);

        itemArea.appendChild(article);

        drop.addEventListener('click', async (event) =>
        {
          event.preventDefault();
          const artID = item.id;
          //console.log(artID);

          if(await DropItem(artID) == true)
          {
            let articles = itemArea.querySelectorAll('article');
            articles.forEach(art =>
            {
                let thisArtID = art.getAttribute('itemID');
                //console.log(thisArtID);
                if(thisArtID == artID)
                {
                  itemArea.removeChild(art);                
                }
            });
          }
        });
      });      
    }
    else
    {
      itemArea.innerHTML = '';      
    }
  }
  catch (error)
  {
    CatchError(error);    
  }
}

async function LoadLocationItems()
{
  try
  {

  }
  catch(error)
  {
    CatchError();
  }
}

async function CreateItem()
{

}

function CatchError(error)
{
  const par = document.getElementById('server-response');
  const content = document.createTextNode(error);
  par.appendChild(content);
  console.error('error:', error);
  localStorage.setItem('lastError', error);
}

function ParametrizeArray(data)
{
  return Object.keys(data)
  .map(key => encodeURIComponent(key) + "=" + encodeURIComponent(data[key]))
  .join("&");
} // TENGO QUE CORREGIR SelectItemType !!!

////////////////////////////////////////////////////////////////////

async function DropItem(id)
{
  const select = document.getElementById('select-characters');
  const selectedOption = select.options[select.selectedIndex];
  const charid = selectedOption.getAttribute('idchar');
    
  let item = {itemID: id, charID: charid};
  let params = ParametrizeArray(item);
  try
  {
    const response = await fetch('http://localhost' + '/itemDrop?' + params,
    {
      method: "GET",
      headers: {                
      "Content-Type": "application/json",      
      }
    });
    if(response.ok)
    {      
      return true;
    }
    else
    {
      console.log('error');
      return false;
    }
  }
  catch(error)
  {
    CatchError(error);
    return false;
  }  
}