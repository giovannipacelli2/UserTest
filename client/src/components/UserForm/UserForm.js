import React, {useState} from 'react';

const UserForm = ({action, initialData, cssClass}) => {

	const defaultData = {
		name : '',
		surname : '',
		email : '',
		birthDate : ''
	};

	const [ data, setData ] = useState(initialData);
	
	const handleChange = (e) => {
		
		if (!e.target) return;
		
		let name = e.target.name;
		let value = e.target.value;
		
		setData((prevState)=>{
			return {
				...prevState,
				[name] : value
			};
		});
	};
	
	const handleSubmit = async (e) => {

		e.preventDefault();
		
		action(data);
		
		setData(defaultData);
	};

		
		  return (
			<form onSubmit={handleSubmit} className={cssClass}>
				<label htmlFor="name">Nome</label>
				<input
					type='text'
					id='name'
					name='name'
					value={data.name}
					onChange={(e)=>{handleChange(e)}}
				/>
				<label htmlFor="surname">Cognome</label>
				<input
					type='text'
					id='surname'
					name='surname'
					value={data.surname}
					onChange={(e)=>{handleChange(e)}}
				/>
				<label htmlFor="email">E-mail</label>
				<input
					type='email'
					id='email'
					name='email'
					value={data.email}
					onChange={(e)=>{handleChange(e)}}
				/>
				<label htmlFor="birthDate">Data di nascita</label>
				<input
					type='date'
					id='birthDate'
					name='birthDate'
					value={data.birthDate}
					onChange={(e)=>{handleChange(e)}}
				/>
				<div className="btn-container">
					<button type="submit" className='btn'>Save</button>
				</div>
			</form>
  )


}

UserForm.defaultProps = {
	initialData : {
		name : '',
		surname : '',
		email : '',
		birthDate : ''
	}, 
	cssClass : ''
};

export default UserForm