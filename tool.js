document.addEventListener("DOMContentLoaded", load);

async function load()
{
    PopulateItemType();
    PopulateCharacters();
    document.getElementById('select-itemType').addEventListener('change', () =>
    {
      SelectItemType();
    });
    document.getElementById('test-button').addEventListener('click', () =>
    {
      TestServer();
    });
    document.getElementById('clear-button').addEventListener('click', () => 
    {
      ClearMessage();
    });
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
    data.forEach(item =>
      {
        const option = document.createElement('option');
        option.value = item.name;
        option.textContent = item.name;
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
  const selectItemType = document.getElementById('select-characters').value;
  if(selectItemType != null || selectItemType != '')
  {
    try
    {

    }
    catch(error)
    {
      CatchError(error);
    }
  }
}

async function SelectItemType()
{    
  const selectItemType = document.getElementById('select-itemType').value;
  if(selectItemType != null || selectItemType != '')
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

      let attElement = document.getElementById('attributes');
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
}

async function LoadItems()
{
  const container = document.getElementById('container');

  try
  {
    const response = await fetch('http://localhost' + '/test',
    {
        method: "GET",
        headers: {                
        "Content-Type": "text/plain",
        }
    });
    let data = await response.json();
    console.log(data);
  }
  catch (error)
  {
    CatchError(error);
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
  return `EXCEPTION: ${error}`;
}